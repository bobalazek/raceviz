<?php

namespace App\Repository;

use App\Entity\TeamVehicle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TeamVehicle|null find($id, $lockMode = null, $lockVersion = null)
 * @method TeamVehicle|null findOneBy(array $criteria, array $orderBy = null)
 * @method TeamVehicle[]    findAll()
 * @method TeamVehicle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeamVehicleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TeamVehicle::class);
    }
}
