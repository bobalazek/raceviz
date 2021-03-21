<?php

namespace App\Form\Type;

use App\Entity\RaceDriver;
use App\Entity\RaceIncident;
use App\Entity\RaceIncidentRaceDriver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class RaceIncidentRaceDriverType.
 */
class RaceIncidentRaceDriverType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('raceIncident', EntityType::class, [
                'class' => RaceIncident::class,
            ])
            ->add('raceDriver', EntityType::class, [
                'class' => RaceDriver::class,
            ])
            ->add('description', TextareaType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => true,
            'data_class' => RaceIncidentRaceDriver::class,
        ]);
    }
}
