<?php

/*
 * (c) Darko Poposki <darko.poposki@sitewards.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Poposki\ModCom\Util;

class DirectoryReader
{
    /**
     * Reads the directories in a given directory
     *
     * @param string $directory
     * @return array
     */
    public function read($directory)
    {
        $directories = [];
        $iterator    = new \FilesystemIterator($directory, \FilesystemIterator::SKIP_DOTS);

        foreach ($iterator as $file) {
            if ($file->isDir()) {
                $directories[$file->getFilename()] = $file->getPathname();
            }
        }

        return $directories;
    }
}
