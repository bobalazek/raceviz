<?php

namespace App\Form\Type\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class TimeDurationToStringTransformer implements DataTransformerInterface
{
    private const POSSIBLE_FORMATS = [
        'H:i:s.v',
        'G:i:s.v',
        'i:s.v',
        's.v',
    ];

    public $format;

    public function __construct(string $format)
    {
        $this->format = $format;
    }

    public function transform($data)
    {
        if ($data instanceof \DateTimeInterface) {
            $value = $data->format($this->format);
            if (!$value) {
                throw new TransformationFailedException('Wrong input format.');
            }

            return $value;
        }

        return '';
    }

    public function reverseTransform($data)
    {
        if (!$data) {
            return null;
        }

        foreach (self::POSSIBLE_FORMATS as $format) {
            $value = \DateTime::createFromFormat($format, $data);

            if ($value) {
                return $value;
            }
        }

        throw new TransformationFailedException('Wrong input format.');
    }
}
