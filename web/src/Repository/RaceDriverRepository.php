<?php

namespace App\Repository;

use App\Entity\RaceDriver;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RaceDriver|null find($id, $lockMode = null, $lockVersion = null)
 * @method RaceDriver|null findOneBy(array $criteria, array $orderBy = null)
 * @method RaceDriver[]    findAll()
 * @method RaceDriver[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RaceDriverRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RaceDriver::class);
    }
}
