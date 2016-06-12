<?php

/*
 * (c) Darko Poposki <darko.poposki@sitewards.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Poposki\ModCom\Composer;

use Poposki\ModCom\Console\Config;

class FileMagentoBuilder
{
    /**
     * @param Config $config
     * @return File
     */
    public static function create(Config $config)
    {
        return FileBuilder::create()
            ->setType('magento-module')
            ->setHomepage($config->get('module/homepage'))
            ->setLicense($config->get('module/license'))
            ->addPackages($config->get('module/packages'))
            ->addRepositories($config->get('module/repositories'))
        ;
    }
}
