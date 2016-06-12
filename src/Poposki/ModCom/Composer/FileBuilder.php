<?php
/*
 * (c) Darko Poposki <darko.poposki@sitewards.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Poposki\ModCom\Composer;

class FileBuilder
{
    /**
     * @return File
     */
    public static function create()
    {
        return new File();
    }
}
