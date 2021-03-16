<?php

namespace App\Manager;

use App\Entity\Race;
use App\Entity\RaceDriver;
use App\Entity\SeasonDriver;
use App\Repository\SeasonDriverRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class RaceDriverManager.
 */
class RaceDriverManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @return Race[]
     */
    public function prepareAll(Race $race)
    {
        $season = $race->getSeason();

        /** @var SeasonDriverRepository $seasonDriverRepository */
        $seasonDriverRepository = $this->em->getRepository(SeasonDriver::class);

        $seasonDrivers = $seasonDriverRepository->findBy([
            'season' => $season,
            'temporary' => false,
        ]);

        /** @var RaceDriverRepository $raceDriverRepository */
        $raceDriverRepository = $this->em->getRepository(RaceDriver::class);

        $raceDrivers = $raceDriverRepository->findBy([
            'race' => $race,
        ]);

        $raceDriversMap = [];
        foreach ($raceDrivers as $raceDriver) {
            $driverId = $raceDriver->getDriver()->getId();
            $raceDriversMap[$driverId] = $raceDriver;
        }

        $addedRaceDrivers = [];
        foreach ($seasonDrivers as $seasonDriver) {
            $driver = $seasonDriver->getDriver();
            $team = $seasonDriver->getTeam();
            $driverId = $driver->getId();
            if (isset($raceDriversMap[$driverId])) {
                continue;
            }

            $raceDriver = new RaceDriver();
            $raceDriver
                ->setRace($race)
                ->setDriver($driver)
                ->setTeam($team)
            ;

            $this->em->persist($raceDriver);

            $addedRaceDrivers[] = $raceDriver;
        }

        $this->em->flush();

        return $addedRaceDrivers;
    }
}
