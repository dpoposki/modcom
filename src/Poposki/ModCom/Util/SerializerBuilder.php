<?php

/*
 * (c) Darko Poposki <darko.poposki@sitewards.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Poposki\ModCom\Util;

use Poposki\ModCom\Composer\FileNormalizer;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;

class SerializerBuilder
{
    /**
     * @return Serializer
     */
    public static function create()
    {
        $encoders = [
            new JsonEncoder(new JsonEncode(JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), new JsonDecode())
        ];
        $normalizers = [
            new FileNormalizer()
        ];

        return new Serializer($normalizers, $encoders);
    }
}
