<?php

namespace App\Manager;

use App\Entity\Race;
use App\Entity\RaceDriver;
use App\Repository\RaceDriverRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class RaceManager.
 */
class RaceManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(
        EntityManagerInterface $em
    ) {
        $this->em = $em;
    }

    public function getAppData(Race $race)
    {
        $data = [
            'race' => $race->toArray(),
            'race_drivers' => [],
        ];

        /** @var RaceDriverRepository $raceDriverRepository */
        $raceDriverRepository = $this->em->getRepository(RaceDriver::class);
        $raceDrivers = $raceDriverRepository
            ->createQueryBuilder('rd')
            ->leftJoin('rd.startingGrid', 'rdsg')
            ->where('rd.race = :race')
            ->orderBy('rdsg.position DESC')
            ->setParameter('race', $race)
        ;
        foreach ($raceDrivers as $raceDriver) {
            $data['race_drivers'][] = $raceDriver->toArray();
        }

        return $data;
    }
}
