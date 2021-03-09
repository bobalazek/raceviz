<?php

namespace App\Repository;

use App\Entity\Constructor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Constructor|null find($id, $lockMode = null, $lockVersion = null)
 * @method Constructor|null findOneBy(array $criteria, array $orderBy = null)
 * @method Constructor[]    findAll()
 * @method Constructor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConstructorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Constructor::class);
    }
}
