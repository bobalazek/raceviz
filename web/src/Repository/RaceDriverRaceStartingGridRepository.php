<?php

namespace App\Repository;

use App\Entity\RaceDriverRaceStartingGrid;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RaceDriverRaceStartingGrid|null find($id, $lockMode = null, $lockVersion = null)
 * @method RaceDriverRaceStartingGrid|null findOneBy(array $criteria, array $orderBy = null)
 * @method RaceDriverRaceStartingGrid[]    findAll()
 * @method RaceDriverRaceStartingGrid[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RaceDriverRaceStartingGridRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RaceDriverRaceStartingGrid::class);
    }
}
