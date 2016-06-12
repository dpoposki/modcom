<?php

/*
 * (c) Darko Poposki <darko.poposki@sitewards.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Poposki\ModCom\Command\ConvertCommand;
use Poposki\ModCom\Console\Application;
use Poposki\ModCom\Console\Config;
use Poposki\ModCom\Util\SerializerBuilder;
use Poposki\ModCom\Vcs\Git;
use Poposki\ModCom\Converter;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Parser;

if (!file_exists(Config::CONFIG_FILE)) {
    echo 'Please provide a configuration file in order to convert your project. Read more in the docs on xxx';
    exit(1);
}

$parser = new Parser();
$config = array_replace_recursive(
    $parser->parse(file_get_contents(__DIR__ . '/config/config.yml')),
    $parser->parse(file_get_contents(Config::CONFIG_FILE))
);

$converter = new Converter(
    new Config($config),
    SerializerBuilder::create(),
    new Filesystem(),
    new Git()
);

$app = new Application();
$app->add(new ConvertCommand($converter));
$app->run();
