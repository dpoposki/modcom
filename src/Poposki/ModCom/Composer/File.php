<?php

/*
 * (c) Darko Poposki <darko.poposki@sitewards.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Poposki\ModCom\Composer;

class File
{
    /** @var string */
    private $name;

    /** @var string */
    private $description;

    /** @var string */
    private $type;

    /** @var string */
    private $homepage;

    /** @var string */
    private $license;

    /** @var string[] */
    private $packages;

    /** @var Repository[] */
    private $repositories;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getHomepage()
    {
        return $this->homepage;
    }

    /**
     * @param $homepage
     * @return $this
     */
    public function setHomepage($homepage)
    {
        $this->homepage = $homepage;

        return $this;
    }

    /**
     * @return string
     */
    public function getLicense()
    {
        return $this->license;
    }

    /**
     * @param $license
     * @return $this
     */
    public function setLicense($license)
    {
        $this->license = $license;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getPackages()
    {
        return $this->packages;
    }

    /**
     * @param array $packages
     * @return $this
     */
    public function addPackages(array $packages)
    {
        foreach ($packages as $packageName => $packageVersion) {
            $this->addPackage($packageName, $packageVersion);
        }

        return $this;
    }

    /**
     * @param string $packageName
     * @param string $packageVersion
     * @return $this
     */
    public function addPackage($packageName, $packageVersion)
    {
        $this->packages[$packageName] = $packageVersion;

        return $this;
    }

    /**
     * @return Repository[]
     */
    public function getRepositories()
    {
        return !empty($this->repositories) ? array_values($this->repositories) : [];
    }

    /**
     * @param array $repositories
     * @return $this
     */
    public function addRepositories(array $repositories)
    {
        foreach ($repositories as $repositoryUrl => $repositoryType) {
            $this->addRepository($repositoryUrl, $repositoryType);
        }

        return $this;
    }

    /**
     * @param string $repositoryUrl
     * @param string $repositoryType
     * @return $this
     */
    public function addRepository($repositoryUrl, $repositoryType)
    {
        $this->repositories[$repositoryUrl] = new Repository($repositoryUrl, $repositoryType);

        return $this;
    }
}
