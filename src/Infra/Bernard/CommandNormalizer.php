<?php

namespace WakeOnWeb\ServiceBusBundle\Infra\Bernard;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Bernard\Envelope;
use UnexpectedValueException;

/**
 * Serialize/Deserialize prooph commands to communicate via rabbitmq.
 *
 * @uses NormalizerInterface
 * @uses DenormalizerInterface
 * @uses SerializerAwareInterface
 * @author Stephane PY <s.py@wakeonweb.com>
 */
class CommandNormalizer implements NormalizerInterface, DenormalizerInterface, SerializerAwareInterface
{
    private $serializer;

    public function normalize($object, $format = null, array $context = array())
    {
        return serialize($object);
    }

    public function denormalize($data, $class, $format = null, array $context = array())
    {
        return unserialize($data);
    }

    public function supportsNormalization($data, $format = null)
    {
        if ($data instanceof CommandMessage) {
            return true;
        }

        return $data instanceof Envelope && $data->getMessage() instanceof CommandMessage;
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        if ($type !== Envelope::class && $type !== CommandMessage::class) {
            return false;
        }

        $object = unserialize($data);

        if (false === is_object($object)) {
            return false;
        }

        if ($object instanceof CommandMessage) {
            return true;
        }

        return $object instanceof Envelope && $object->getMessage() instanceof CommandMessage;
    }

    public function setSerializer(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }
}
