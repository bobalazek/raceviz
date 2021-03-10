<?php

namespace App\Repository;

use App\Entity\RaceCarDriverRaceStartingGridPosition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RaceCarDriverRaceStartingGridPosition|null find($id, $lockMode = null, $lockVersion = null)
 * @method RaceCarDriverRaceStartingGridPosition|null findOneBy(array $criteria, array $orderBy = null)
 * @method RaceCarDriverRaceStartingGridPosition[]    findAll()
 * @method RaceCarDriverRaceStartingGridPosition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RaceCarDriverRaceStartingGridPositionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RaceCarDriverRaceStartingGridPosition::class);
    }
}
