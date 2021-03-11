<?php

namespace App\Repository;

use App\Entity\SeasonDriver;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SeasonDriver|null find($id, $lockMode = null, $lockVersion = null)
 * @method SeasonDriver|null findOneBy(array $criteria, array $orderBy = null)
 * @method SeasonDriver[]    findAll()
 * @method SeasonDriver[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SeasonDriverRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SeasonDriver::class);
    }
}
