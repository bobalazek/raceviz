<?php

namespace App\Manager;

use App\Entity\Race;
use App\Entity\RaceDriver;
use App\Entity\RaceDriverRaceLap;
use App\Entity\RaceDriverRacePitStop;
use App\Entity\SeasonDriver;
use App\Form\Type\RaceDriverRaceLapType;
use App\Form\Type\RaceDriverRacePitStopType;
use App\Repository\RaceDriverRaceLapRepository;
use App\Repository\RaceDriverRacePitStopRepository;
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
     * @return RaceDriver[] newly added race drivers
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

        $addedRaceDrivers = [];
        foreach ($seasonDrivers as $seasonDriver) {
            $raceDriver = new RaceDriver();
            $raceDriver
                ->setRace($race)
                ->setSeasonDriver($seasonDriver)
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
    public function saveLaps(
        RaceDriver $raceDriver,
        array $formData,
        bool $useExistingDataIfNotSet = false,
        bool $flushIfNoErrors = true
    ) {
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
            $lapData = (int) $single['lap'];
            $raceLapData = $single['race_lap'];
            $racePitStopData = $single['race_pit_stop'];
            $hadRacePitStopData = isset($single['had_race_pit_stop']) && $single['had_race_pit_stop'];

            // Race Lap
            /** @var RaceDriverRaceLap $raceDriverRaceLap */
            $raceDriverRaceLap = isset($raceDriverRaceLapsMap[$lapData])
                ? $raceDriverRaceLapsMap[$lapData]
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
                'lap' => $lapData,
                'position' => $useExistingDataIfNotSet && !isset($raceLapData['position'])
                    ? $raceDriverRaceLap->getPosition()
                    : $raceLapData['position'] ?? null,
                'timeDuration' => $useExistingDataIfNotSet && !isset($raceLapData['time_duration'])
                    ? $raceDriverRaceLap->getTimeDuration()
                    : $raceLapData['time_duration'] ?? null,
                'timeOfDay' => $useExistingDataIfNotSet && !isset($raceLapData['time_of_day'])
                    ? $raceDriverRaceLap->getTimeOfDay()
                    : $raceLapData['time_of_day'] ?? null,
                'tyres' => $useExistingDataIfNotSet && !isset($raceLapData['tyres'])
                    ? $raceDriverRaceLap->getTyres()
                    : $raceLapData['tyres'] ?? null,
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

            if (!$hadRacePitStopData) {
                // We habe no pit stop, so just skip the code below ...
                continue;
            }

            // Race Pit Stop
            /** @var RaceDriverRacePitStop $raceDriverRacePitStop */
            $raceDriverRacePitStop = isset($raceDriverRacePitStopsMap[$lapData])
                ? $raceDriverRacePitStopsMap[$lapData]
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
                'lap' => $lapData,
                'timeDuration' => $useExistingDataIfNotSet && !isset($racePitStopData['time_duration'])
                    ? $raceDriverRaceLap->getTimeDuration()
                    : $racePitStopData['time_duration'] ?? null,
                'timeOfDay' => $useExistingDataIfNotSet && !isset($racePitStopData['time_of_day'])
                    ? $raceDriverRaceLap->getTimeOfDay()
                    : $racePitStopData['time_of_day'] ?? null,
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

        if (
            !$errors &&
            $flushIfNoErrors
        ) {
            $this->em->flush();
        }

        return $errors;
    }
}
