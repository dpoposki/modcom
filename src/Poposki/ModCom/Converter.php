<?php

/*
 * (c) Darko Poposki <darko.poposki@sitewards.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Poposki\ModCom;

use Poposki\ModCom\Composer\File;
use Poposki\ModCom\Composer\FileMagentoBuilder;
use Poposki\ModCom\Composer\FileProjectBuilder;
use Poposki\ModCom\Console\Config;
use Poposki\ModCom\Util\DirectoryReader;
use Poposki\ModCom\Vcs\Git;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Serializer\Serializer;

class Converter
{
    const COMPOSER_FILE_CLASS = 'Poposki\ModCom\Composer\File';

    /** @var File */
    private $file;

    /** @var Config */
    private $config;

    /** @var Serializer */
    private $serializer;

    /** @var Filesystem */
    private $filesystem;

    /** @var Git */
    private $git;

    /**
     * @param Config $config
     * @param Serializer $serializer
     * @param Filesystem $filesystem
     * @param Git $git
     */
    public function __construct(
        Config $config,
        Serializer $serializer,
        Filesystem $filesystem,
        Git $git
    )
    {
        $this->config     = $config;
        $this->serializer = $serializer;
        $this->filesystem = $filesystem;
        $this->git        = $git;
    }

    /**
     * @param SymfonyStyle $io
     */
    public function convert(SymfonyStyle $io)
    {
        $io->title(
            sprintf('<info>Transformation for project %s started</info>', $this->config->get('project/name'))
        );

        $io->text('<info>Checking for git submodules...</info>');
        $this->git->setSubmodules();

        $io->text('<info>Parsing dependencies from the modman directory</info>');

        $reader      = new DirectoryReader();
        $directories = $reader->read($this->config->get('modman/dir'));

        $this->filesystem->mkdir($this->config->get('temp_dir'));

        // generate new project specific composer file
        $this->file = FileProjectBuilder::create($this->config);

        foreach ($directories as $directoryName => $directoryPath) {
            $directoryPath = str_replace('\\', '/', $directoryPath);

            if ($this->filesystem->exists($directoryPath . '/composer.json')) {
                $this->handleComposerPackage($directoryPath, $io);
            } else {
                $this->handleLocalPackage($directoryName, $io);
            }
        }

        $composerContent = $this->serializer->serialize($this->file, 'json');

        $this->filesystem->dumpFile('composer.json', $composerContent);

        // cleanup
        $this->cleanup($io);

        $io->success('Transforming done');
    }

    /**
     * @param string $directoryPath
     * @param SymfonyStyle $io
     */
    private function handleComposerPackage($directoryPath, SymfonyStyle $io)
    {
        $composerFile = $this->serializer->deserialize(
            file_get_contents($directoryPath . '/composer.json'),
            self::COMPOSER_FILE_CLASS,
            'json'
        );

        $this->file->addPackage($composerFile->getName(), '*');

        if ($gitSubmodule = $this->git->getSubmodule($directoryPath)) {
            $this->file->addRepository($gitSubmodule, 'vcs');
            $this->git->unsetSubmodule($gitSubmodule);
        }

        $io->text(
            sprintf(" - Added composer package <info>%s</info>\n", $composerFile->getName())
        );
    }

    /**
     * @param string $directoryName
     * @param SymfonyStyle $io
     */
    private function handleLocalPackage($directoryName, SymfonyStyle $io)
    {
        $dirParts = explode(
            $this->config->get('modman/dir_separator'),
            ucwords($directoryName, $this->config->get('modman/dir_separator'))
        );

        if (!isset($dirParts[1])) {
            list($vendor, $module) = [$this->config->get('project/vendor'), $dirParts[0]];
        } else {
            list($vendor, $module) = $dirParts;
        }

        $modulePath  = implode('/', [$this->config->get('temp_dir'), $vendor, $module]);
        $packageName = implode('/', [strtolower($vendor), strtolower($module)]);

        $composerFile = FileMagentoBuilder::create($this->config)
            ->setName($packageName)
            ->setDescription($vendor . ' ' . $module);

        $composerContent = $this->serializer->serialize($composerFile, 'json');

        if (!$this->filesystem->exists($modulePath)) {
            $this->filesystem->mkdir(dirname($modulePath));
        }

        $this->filesystem->rename(
            $this->config->get('modman/dir') . '/' . $directoryName,
            $modulePath
        );

        // cleanup the .git file if it was a git submodule
        $this->filesystem->remove($modulePath . '/.git');
        $this->filesystem->dumpFile($modulePath . '/composer.json', $composerContent);

        $this->file->addPackage($packageName, '*@dev');
        $this->file->addRepository(sprintf('src/%s/*', $vendor), 'path');

        $io->text(
            sprintf(" - Installed local package <info>%s</info>\n", $packageName)
        );
    }

    /**
     * @param SymfonyStyle $io
     */
    private function cleanup(SymfonyStyle $io)
    {
        $io->text('<info>Cleaning up...</info>');
        $io->text(sprintf(" - Removing modman and source directory...\n"));

        $this->filesystem->remove([
            $this->config->get('modman/dir'),
            $this->config->get('project/source_dir')
        ]);

        $this->filesystem->rename(
            $this->config->get('temp_dir'),
            $this->config->get('project/source_dir')
        );

        $io->text(sprintf(" - Source directory generated with the local packages\n"));

        if ($gitSubmodules = $this->git->getSubmodules()) {
            $io->note('Each of following packages have its own repository but it is not composer ready');

            foreach ($gitSubmodules as $path => $url) {
                $module = explode('/', $path);
                $module = end($module);

                $io->text(sprintf(" - <info>%s</info> on repository %s\n", $module, $url));
            }
        }
    }
}
