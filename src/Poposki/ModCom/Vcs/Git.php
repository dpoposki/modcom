<?php

/*
 * (c) Darko Poposki <darko.poposki@sitewards.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Poposki\ModCom\Vcs;

class Git
{
    const MODULES_FILE = '.gitmodules';

    /** @var string[] */
    private $submodules;

    /**
     * @param string $submodule
     * @return string|null
     */
    public function getSubmodule($submodule)
    {
        return (array_key_exists($submodule, $this->submodules))
            ? $this->submodules[$submodule]
            : null;
    }

    /**
     * @return string[]
     */
    public function getSubmodules()
    {
        return $this->submodules;
    }

    /**
     * Sets the submodules if the git file is found
     */
    public function setSubmodules()
    {
        if (file_exists(self::MODULES_FILE)) {
            $content = explode("\n", file_get_contents(self::MODULES_FILE));

            for ($i = 0; $i < count($content) - 3; $i += 3) {
                $path = $this->parseSubmoduleLine($content[$i+1]);
                $url  = $this->parseSubmoduleLine($content[$i+2]);

                $this->submodules[$path] = $url;
            }
        }
    }

    /**
     * @param string $submodule
     */
    public function unsetSubmodule($submodule)
    {
        if (array_key_exists($submodule, $this->submodules)) {
            unset($this->submodules[$submodule]);
        }
    }

    /**
     * @param string $line
     * @return string|null
     */
    private function parseSubmoduleLine($line)
    {
        $line = preg_replace('/\s+/', '', $line);
        $line = explode('=', $line);

        return isset($line[1]) ? $line[1] : null;
    }
}
