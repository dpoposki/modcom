<?php

/*
 * (c) Darko Poposki <darko.poposki@sitewards.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Poposki\ModCom\Composer;

use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class FileNormalizer extends AbstractNormalizer
{
    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return class_exists($type);
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        return FileBuilder::create()
            ->setName($data->name);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return is_object($data) && !$data instanceof \Traversable;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = [])
    {
        $data = [];

        if ($object->getName()) {
            $data['name'] = $object->getName();
        }

        if ($object->getDescription()) {
            $data['description'] = $object->getDescription();
        }

        if ($object->getType()) {
            $data['type'] = $object->getType();
        }

        if ($object->getHomepage()) {
            $data['homepage'] = $object->getHomepage();
        }

        if ($object->getLicense()) {
            $data['license'] = $object->getLicense();
        }

        if ($object->getPackages()) {
            $data['require'] = $object->getPackages();
        }

        if ($object->getRepositories()) {
            $data['repositories'] = array_map(
                function (Repository $repository) {
                    return [
                        'type' => $repository->getType(),
                        'url'  => $repository->getUrl()
                    ];
                },
                $object->getRepositories()
            );
        }

        return $data;
    }
}
