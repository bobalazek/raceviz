<?php

namespace App\Repository;

use App\Entity\RaceDriverRaceResult;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RaceDriverRaceResult|null find($id, $lockMode = null, $lockVersion = null)
 * @method RaceDriverRaceResult|null findOneBy(array $criteria, array $orderBy = null)
 * @method RaceDriverRaceResult[]    findAll()
 * @method RaceDriverRaceResult[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RaceDriverRaceResultRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RaceDriverRaceResult::class);
    }
}
