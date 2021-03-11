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
            'invalid_message' => 'Invalid input.',
            'format' => 'H:i:s.v',
        ]);
    }

    public function getBlockPrefix()
    {
        return 'time_duration';
    }
}
