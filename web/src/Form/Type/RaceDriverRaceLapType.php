<?php

namespace App\Form\Type;

use App\Entity\RaceDriver;
use App\Entity\RaceDriverRaceLap;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class RaceDriverRaceLapType.
 */
class RaceDriverRaceLapType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('raceDriver', EntityType::class, [
                'class' => RaceDriver::class,
            ])
            ->add('lap', NumberType::class)
            ->add('position', NumberType::class)
            ->add('time', TimeDurationType::class)
            ->add('timeOfDay', TimeType::class)
            ->add('tyres', TyresType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => true,
            'data_class' => RaceDriverRaceLap::class,
            'filter_race' => null,
        ]);
    }
}
