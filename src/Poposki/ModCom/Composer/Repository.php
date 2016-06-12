<?php

/*
 * (c) Darko Poposki <darko.poposki@sitewards.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Poposki\ModCom\Composer;

class Repository
{
    /** @var string */
    private $type;

    /** @var string */
    private $url;

    /**
     * @param string $url
     * @param string $type
     */
    public function __construct($url, $type)
    {
        $this->url  = $url;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
}
