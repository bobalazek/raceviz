<?php

namespace App\Repository;

use App\Entity\RaceIncident;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RaceIncident|null find($id, $lockMode = null, $lockVersion = null)
 * @method RaceIncident|null findOneBy(array $criteria, array $orderBy = null)
 * @method RaceIncident[]    findAll()
 * @method RaceIncident[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RaceIncidentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RaceIncident::class);
    }
}
