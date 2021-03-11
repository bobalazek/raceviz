<?php

namespace App\Repository;

use App\Entity\SeasonTeam;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SeasonTeam|null find($id, $lockMode = null, $lockVersion = null)
 * @method SeasonTeam|null findOneBy(array $criteria, array $orderBy = null)
 * @method SeasonTeam[]    findAll()
 * @method SeasonTeam[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SeasonTeamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SeasonTeam::class);
    }
}
