<?php

namespace App\Repository;

use App\Entity\RaceCarDriverRaceGridPosition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RaceCarDriverRaceGridPosition|null find($id, $lockMode = null, $lockVersion = null)
 * @method RaceCarDriverRaceGridPosition|null findOneBy(array $criteria, array $orderBy = null)
 * @method RaceCarDriverRaceGridPosition[]    findAll()
 * @method RaceCarDriverRaceGridPosition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RaceCarDriverRaceGridPositionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RaceCarDriverRaceGridPosition::class);
    }
}
