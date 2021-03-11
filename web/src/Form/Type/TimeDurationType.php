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

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addViewTransformer($this);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'compound' => false,
            'invalid_message' => 'You must input the data in the following format: "minutes:seconds.miliseconds" (1:40.950)',
        ]);
    }

    public function getBlockPrefix()
    {
        return 'time_duration';
    }

    public function transform($data)
    {
        if ($data instanceof \DateTimeInterface) {
            $value = $data->format(self::TIME_FORMAT);
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

        $dataExploded = explode(':', $data);
        if (2 !== count($dataExploded)) {
            throw new TransformationFailedException('Wrong input format.');
        }

        $dataLastExploded = explode('.', end($dataExploded));
        if (2 !== count($dataLastExploded)) {
            throw new TransformationFailedException('Wrong input format.');
        }

        $value = \DateTime::createFromFormat(self::TIME_FORMAT, $data);
        if (!$value) {
            throw new TransformationFailedException('Wrong input format.');
        }

        return $value;
    }
}
