<?php

namespace App\Form\Type;

use App\Form\Type\DataTransformer\TimeDurationToStringTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TimeDurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addViewTransformer(new TimeDurationToStringTransformer($options['format']));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'with_hours' => false,
            'compound' => false,
            'invalid_message' => 'Invalid input. Please make sure that you delimited seconds/minutes/hours with a colon (:) instead of a dot (.).',
            'format' => 'H:i:s.v',
            'help' => 'Enter a valid duration time (1:06:20.123 or 1:09.456 or 55.789).',
        ]);
    }

    public function getBlockPrefix()
    {
        return 'time_duration';
    }
}
