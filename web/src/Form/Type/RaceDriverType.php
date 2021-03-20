<?php

namespace App\Form\Type;

use App\Entity\RaceDriver;
use App\Entity\RaceDriverRaceResult;
use App\Entity\RaceDriverRaceStartingGrid;
use App\Entity\SeasonDriver;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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
        $withActualStartingGridAndResultData = $options['with_actual_starting_grid_and_result_data'];

        $builder
            ->add('seasonDriver', EntityType::class, [
                'class' => SeasonDriver::class,
                'query_builder' => function (EntityRepository $er) use ($filterRace) {
                    $queryBuilder = $er
                        ->createQueryBuilder('sd')
                        ->leftJoin('sd.driver', 'd')
                        ->orderBy('d.lastName')
                        ->addOrderBy('d.firstName')
                    ;

                    if ($filterRace) {
                        $queryBuilder
                            ->where('sd.season = :season')
                            ->setParameter('season', $filterRace->getSeason())
                        ;
                    }

                    return $queryBuilder;
                },
            ])
        ;

        if ($withActualStartingGridAndResultData) {
            $builder
                ->add('raceDriverRaceStartingGrid', RaceDriverRaceStartingGridType::class, [
                    'required' => false,
                ])
                ->add('raceDriverRaceResult', RaceDriverRaceResultType::class, [
                    'required' => false,
                ])
            ;
        } else {
            $builder
                ->add('raceDriverRaceStartingGrid', EntityType::class, [
                    'class' => RaceDriverRaceStartingGrid::class,
                    'required' => false,
                ])
                ->add('raceDriverRaceResult', EntityType::class, [
                    'class' => RaceDriverRaceResult::class,
                    'required' => false,
                ])
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => true,
            'data_class' => RaceDriver::class,
            'filter_race' => null,
            'with_actual_starting_grid_and_result_data' => false,
        ]);
    }
}
