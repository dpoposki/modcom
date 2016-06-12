<?php

/*
 * (c) Darko Poposki <darko.poposki@sitewards.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Poposki\ModCom\Composer;

use Poposki\ModCom\Console\Config;

class FileProjectBuilder
{
    /**
     * @param Config $config
     * @return File
     */
    public static function create(Config $config)
    {
        return FileBuilder::create()
            ->setName($config->get('project/name'))
            ->setDescription($config->get('project/description'))
            ->setType('project')
            ->setHomepage($config->get('project/homepage'))
            ->setLicense($config->get('project/license'))
            ->addPackages($config->get('project/packages'))
            ->addRepositories($config->get('project/repositories'))
        ;
    }
}
