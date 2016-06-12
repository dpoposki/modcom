<?php

/*
 * (c) Darko Poposki <darko.poposki@sitewards.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Poposki\ModCom\Console;

class Config
{
    const CONFIG_FILE = 'modcom.yml';

    /** @var array */
    private $config = [];

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $path
     *
     * @return mixed|null
     */
    public function get($path)
    {
        $path = explode('/', $path);

        $part  = $this->config;
        $value = null;

        foreach ($path as $key) {
            if (!isset($part[$key])) {
                $value = null;
                break;
            }

            $value = $part[$key];
            $part  = $part[$key];
        }

        return $value;
    }
}
