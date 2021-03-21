<?php

namespace App\Form\Type;

use App\Entity\RaceDriver;
use App\Entity\RaceIncident;
use App\Entity\RaceIncidentRaceDriver;
use Doctrine\ORM\EntityRepository;
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
        $filterRace = $options['filter_race'];

        $builder
            ->add('raceIncident', EntityType::class, [
                'class' => RaceIncident::class,
                'query_builder' => function (EntityRepository $er) use ($filterRace) {
                    $queryBuilder = $er->createQueryBuilder('ri');

                    if ($filterRace) {
                        $queryBuilder
                            ->where('ri.race = :race')
                            ->setParameter('race', $filterRace)
                        ;
                    }

                    return $queryBuilder;
                },
            ])
            ->add('raceDriver', EntityType::class, [
                'class' => RaceDriver::class,
                'query_builder' => function (EntityRepository $er) use ($filterRace) {
                    $queryBuilder = $er->createQueryBuilder('rd');

                    if ($filterRace) {
                        $queryBuilder
                            ->where('rd.race = :race')
                            ->setParameter('race', $filterRace)
                        ;
                    }

                    return $queryBuilder;
                },
            ])
            ->add('description', TextareaType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => true,
            'data_class' => RaceIncidentRaceDriver::class,
            'filter_race' => null,
        ]);
    }
}
