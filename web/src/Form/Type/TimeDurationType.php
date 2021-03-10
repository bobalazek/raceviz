<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TimeDurationType extends AbstractType implements DataTransformerInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ('' === $options['empty_data']) {
            $builder->addViewTransformer($this);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'compound' => false,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'duration';
    }

    public function transform($data)
    {
        return $data;
    }

    public function reverseTransform($data)
    {
        return null === $data ? '' : $data;
    }
}
