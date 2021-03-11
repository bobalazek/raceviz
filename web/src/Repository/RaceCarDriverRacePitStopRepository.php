<?php

namespace App\Repository;

use App\Entity\RaceCarDriverRacePitStop;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RaceCarDriverRacePitStop|null find($id, $lockMode = null, $lockVersion = null)
 * @method RaceCarDriverRacePitStop|null findOneBy(array $criteria, array $orderBy = null)
 * @method RaceCarDriverRacePitStop[]    findAll()
 * @method RaceCarDriverRacePitStop[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RaceCarDriverRacePitStopRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RaceCarDriverRacePitStop::class);
    }
}
