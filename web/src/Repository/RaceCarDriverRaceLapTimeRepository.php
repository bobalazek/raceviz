<?php

namespace App\Repository;

use App\Entity\RaceCarDriverRaceLapTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RaceCarDriverRaceLapTime|null find($id, $lockMode = null, $lockVersion = null)
 * @method RaceCarDriverRaceLapTime|null findOneBy(array $criteria, array $orderBy = null)
 * @method RaceCarDriverRaceLapTime[]    findAll()
 * @method RaceCarDriverRaceLapTime[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RaceCarDriverRaceLapTimeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RaceCarDriverRaceLapTime::class);
    }
}
