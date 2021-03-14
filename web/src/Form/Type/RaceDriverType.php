<?php

namespace App\Form\Type;

use App\Entity\Driver;
use App\Entity\RaceDriver;
use App\Entity\Team;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class RaceDriverType.
 */
class RaceDriverType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $filterRace = $options['filter_race'];

        $builder
            ->add('driver', EntityType::class, [
                'class' => Driver::class,
                'query_builder' => function (EntityRepository $er) use ($filterRace) {
                    $queryBuilder = $er
                        ->createQueryBuilder('d')
                        ->orderBy('d.lastName')
                        ->addOrderBy('d.firstName')
                    ;

                    if ($filterRace) {
                        $queryBuilder
                            ->where('sd.season = :season')
                            ->setParameter('season', $filterRace->getSeason())
                            ->innerJoin('d.seasonDrivers', 'sd')
                        ;
                    }

                    return $queryBuilder;
                },
            ])
            ->add('team', EntityType::class, [
                'class' => Team::class,
                'query_builder' => function (EntityRepository $er) use ($filterRace) {
                    $queryBuilder = $er
                        ->createQueryBuilder('t')
                        ->orderBy('t.name')
                    ;

                    if ($filterRace) {
                        $queryBuilder
                            ->where('st.season = :season')
                            ->setParameter('season', $filterRace->getSeason())
                            ->innerJoin('t.seasonTeams', 'st')
                        ;
                    }

                    return $queryBuilder;
                },
            ])
            ->add('raceStartingGridPosition', NumberType::class)
            ->add('raceStartingGridTyres', TyresType::class)
            ->add('raceResultPosition', NumberType::class)
            ->add('raceResultPoints', NumberType::class)
            ->add('raceResultTime', TimeDurationType::class)
            ->add('raceResultLapsBehind', NumberType::class)
            ->add('raceResultStatus', RaceDriverStatusType::class)
            ->add('raceResultStatusNote', TextareaType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => true,
            'data_class' => RaceDriver::class,
            'filter_race' => null,
        ]);
    }
}
