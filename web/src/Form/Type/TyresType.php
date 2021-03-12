<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TyresType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'choices' => [
                'Hard' => 'hard',
                'Medium' => 'medium',
                'Soft' => 'soft',
                'Intermediate' => 'intermediate',
                'Wet' => 'wet',
            ],
        ]);
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
