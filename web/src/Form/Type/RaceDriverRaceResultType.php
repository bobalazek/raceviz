<?php

namespace App\Form\Type;

use App\Entity\RaceDriver;
use App\Entity\RaceDriverRaceResult;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class RaceDriverRaceResultType.
 */
class RaceDriverRaceResultType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('raceDriver', EntityType::class, [
                'class' => RaceDriver::class,
            ])
            ->add('position', NumberType::class)
            ->add('points', NumberType::class)
            ->add('timeDuration', TimeDurationType::class)
            ->add('lapsBehind', NumberType::class)
            ->add('status', RaceDriverStatusType::class)
            ->add('statusNote', TextareaType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => true,
            'data_class' => RaceDriverRaceResult::class,
        ]);
    }
}
