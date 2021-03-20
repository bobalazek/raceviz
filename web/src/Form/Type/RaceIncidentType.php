<?php

namespace App\Form\Type;

use App\Entity\Race;
use App\Entity\RaceIncident;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class RaceIncidentType.
 */
class RaceIncidentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('race', EntityType::class, [
                'class' => Race::class,
            ])
            ->add('type', RaceIncidentTypeType::class)
            ->add('description', TextareaType::class)
            ->add('flag', RaceFlagType::class)
            ->add('lap', NumberType::class)
            ->add('lapSector', NumberType::class)
            ->add('lapLocation', NumberType::class)
            ->add('timeDuration', TimeDurationType::class)
            ->add('timeOfDay', TimeType::class, [
                'with_seconds' => true,
                'widget' => 'single_text',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => true,
            'data_class' => RaceIncident::class,
        ]);
    }
}
