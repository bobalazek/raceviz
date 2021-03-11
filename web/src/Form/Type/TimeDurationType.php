<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TimeDurationType extends AbstractType implements DataTransformerInterface
{
    const TIME_FORMAT = 'i:s.v';
    const TIME_WITH_HOURS_FORMAT = 'G:i:s.v';

    public $format = self::TIME_FORMAT; // TODO: any better way to do this?

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->format = $options['with_hours']
            ? self::TIME_WITH_HOURS_FORMAT
            : self::TIME_FORMAT;

        $builder->addViewTransformer($this);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'with_hours' => false,
            'compound' => false,
            'invalid_message' => 'Invalid input.',
        ]);
    }

    public function getBlockPrefix()
    {
        return 'time_duration';
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

        $expectedCount = self::TIME_WITH_HOURS_FORMAT === $this->format
            ? 3
            : 2;

        $dataExploded = explode(':', $data);
        if (count($dataExploded) !== $expectedCount) {
            throw new TransformationFailedException('Wrong input format.');
        }

        $dataLastExploded = explode('.', end($dataExploded));
        if (2 !== count($dataLastExploded)) {
            throw new TransformationFailedException('Wrong input format.');
        }

        $value = \DateTime::createFromFormat($this->format, $data);
        if (!$value) {
            throw new TransformationFailedException('Wrong input format.');
        }

        return $value;
    }
}
