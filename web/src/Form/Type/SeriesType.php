<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeriesType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'choices' => [
                'Formula 1' => 'F1',
                'Formula 2' => 'F2',
                'Formula 3' => 'F3',
                'Formula E' => 'FE',
            ],
        ]);
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
