<?php

namespace App\Repository;

use App\Entity\RaceCarDriver;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RaceCarDriver|null find($id, $lockMode = null, $lockVersion = null)
 * @method RaceCarDriver|null findOneBy(array $criteria, array $orderBy = null)
 * @method RaceCarDriver[]    findAll()
 * @method RaceCarDriver[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RaceCarDriverRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RaceCarDriver::class);
    }
}
