<?php

/*
 * (c) Darko Poposki <darko.poposki@sitewards.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Poposki\ModCom\Command;

use Poposki\ModCom\Converter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ConvertCommand extends Command
{
    /** @var Converter */
    private $converter;

    /**
     * @param Converter $converter
     */
    public function __construct(Converter $converter)
    {
        parent::__construct();

        $this->converter = $converter;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('convert')
            ->setDescription('Converts a modman project to a composer-ready project.')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->converter->convert(
            new SymfonyStyle($input, $output)
        );
    }
}
