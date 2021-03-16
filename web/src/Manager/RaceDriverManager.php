<?php

namespace App\Manager;

use App\Entity\Race;
use App\Entity\RaceDriver;
use App\Entity\RaceDriverRaceLap;
use App\Entity\RaceDriverRacePitStop;
use App\Entity\SeasonDriver;
use App\Form\Type\RaceDriverRaceLapType;
use App\Form\Type\RaceDriverRacePitStopType;
use App\Repository\SeasonDriverRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * Class RaceDriverManager.
 */
class RaceDriverManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var ErrorManager
     */
    private $errorManager;

    public function __construct(
        EntityManagerInterface $em,
        FormFactoryInterface $formFactory,
        ErrorManager $errorManager
    ) {
        $this->em = $em;
        $this->formFactory = $formFactory;
        $this->errorManager = $errorManager;
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

    /**
     * @return array returns any errors
     */
    public function saveLaps(RaceDriver $raceDriver, array $formData)
    {
        $errors = [];

        // Race Laps
        $raceDriverRaceLapsToBeRemoved = [];
        $raceDriverRaceLapsMap = [];
        /** @var RaceDriverRaceLapRepository $raceDriverRaceLapRepository */
        $raceDriverRaceLapRepository = $this->em->getRepository(RaceDriverRaceLap::class);
        $raceDriverRaceLaps = $raceDriverRaceLapRepository
            ->createQueryBuilder('rdrl')
            ->where('rdrl.raceDriver = :raceDriver')
            ->orderBy('rdrl.lap')
            ->setParameter('raceDriver', $raceDriver)
            ->getQuery()
            ->getResult()
        ;
        foreach ($raceDriverRaceLaps as $raceDriverRaceLap) {
            $raceDriverRaceLapsToBeRemoved[] = $raceDriverRaceLap->getId();
            $raceDriverRaceLapsMap[$raceDriverRaceLap->getLap()] = $raceDriverRaceLap;
        }

        // Race Pit Stops
        $raceDriverRacePitStopsToBeRemoved = [];
        $raceDriverRacePitStopsMap = [];
        /** @var RaceDriverRacePitStopRepository $raceDriverRacePitStopRepository */
        $raceDriverRacePitStopRepository = $this->em->getRepository(RaceDriverRacePitStop::class);
        $raceDriverRacePitStops = $raceDriverRacePitStopRepository
            ->createQueryBuilder('rdrps')
            ->where('rdrps.raceDriver = :raceDriver')
            ->orderBy('rdrps.lap')
            ->setParameter('raceDriver', $raceDriver)
            ->getQuery()
            ->getResult()
        ;
        foreach ($raceDriverRacePitStops as $raceDriverRacePitStop) {
            $raceDriverRacePitStopsToBeRemoved[] = $raceDriverRacePitStop->getId();
            $raceDriverRacePitStopsMap[$raceDriverRacePitStop->getLap()] = $raceDriverRacePitStop;
        }

        // Processing
        foreach ($formData as $index => $single) {
            $lap = (int) $single['lap'];
            $raceLap = $single['race_lap'];
            $racePitStop = $single['race_pit_stop'];
            $hadRacePitStop = isset($single['had_race_pit_stop']) && $single['had_race_pit_stop'];

            // Race Lap
            /** @var RaceDriverRaceLap $raceDriverRaceLap */
            $raceDriverRaceLap = isset($raceDriverRaceLapsMap[$lap])
                ? $raceDriverRaceLapsMap[$lap]
                : new RaceDriverRaceLap();

            $raceLapForm = $this->formFactory->create(
                RaceDriverRaceLapType::class,
                $raceDriverRaceLap,
                [
                    'csrf_protection' => false,
                ]
            );
            $raceLapForm->submit([
                'raceDriver' => $raceDriver->getId(),
                'lap' => $lap,
                'position' => $raceLap['position'] ?? null,
                'time' => $raceLap['time'] ?? null,
                'timeOfDay' => $raceLap['time_of_day'] ?? null,
                'tyres' => $raceLap['tyres'] ?? null,
            ]);
            if (!$raceLapForm->isValid()) {
                if (!isset($errors[$index])) {
                    $errors[$index] = [];
                }
                $errors[$index]['race_lap'] = $this->errorManager->getFormErrors($raceLapForm);
            }

            $this->em->persist($raceDriverRaceLap);

            if (($removalKey = array_search(
                $raceDriverRaceLap->getId(),
                $raceDriverRaceLapsToBeRemoved
            )) !== false) {
                unset($raceDriverRaceLapsToBeRemoved[$removalKey]);
            }

            if (!$hadRacePitStop) {
                continue;
            }

            // Race Pit Stop
            /** @var RaceDriverRacePitStop $raceDriverRacePitStop */
            $raceDriverRacePitStop = isset($raceDriverRacePitStopsMap[$lap])
                ? $raceDriverRacePitStopsMap[$lap]
                : new RaceDriverRacePitStop();

            $racePitStopForm = $this->formFactory->create(
                RaceDriverRacePitStopType::class,
                $raceDriverRacePitStop,
                [
                    'csrf_protection' => false,
                ]
            );
            $racePitStopForm->submit([
                'raceDriver' => $raceDriver->getId(),
                'lap' => $lap,
                'time' => $racePitStop['time'] ?? null,
                'timeOfDay' => $racePitStop['time_of_day'] ?? null,
            ]);
            if (!$racePitStopForm->isValid()) {
                if (!isset($errors[$index])) {
                    $errors[$index] = [];
                }
                $errors[$index]['race_pit_stop'] = $this->errorManager->getFormErrors($racePitStopForm);
            }

            $this->em->persist($raceDriverRacePitStop);

            if (($removalKey = array_search(
                $raceDriverRacePitStop->getId(),
                $raceDriverRacePitStopsToBeRemoved
            )) !== false) {
                unset($raceDriverRacePitStopsToBeRemoved[$removalKey]);
            }
        }

        // Cleanup
        foreach ($raceDriverRaceLaps as $raceDriverRaceLap) {
            if (!in_array(
                $raceDriverRaceLap->getId(),
                $raceDriverRaceLapsToBeRemoved
            )) {
                continue;
            }
            $this->em->remove($raceDriverRaceLap);
        }

        foreach ($raceDriverRacePitStops as $raceDriverRacePitStop) {
            if (!in_array(
                $raceDriverRacePitStop->getId(),
                $raceDriverRacePitStopsToBeRemoved
            )) {
                continue;
            }
            $this->em->remove($raceDriverRacePitStop);
        }

        return $errors;
    }
}
