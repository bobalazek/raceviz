<?php

namespace App\Controller\Api\V1;

use App\Entity\Race;
use App\Entity\RaceDriver;
use App\Entity\RaceDriverRaceLap;
use App\Entity\RaceDriverRacePitStop;
use App\Form\Type\RaceDriverRaceLapType;
use App\Form\Type\RaceDriverRacePitStopType;
use App\Form\Type\RaceDriverType;
use App\Repository\RaceDriverRaceLapRepository;
use App\Repository\RaceDriverRacePitStopRepository;
use App\Repository\RaceDriverRepository;
use App\Repository\RaceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RacesController.
 */
class RacesController extends AbstractApiController
{
    /**
     * @Route("/api/v1/races/{raceSlug}", name="api.v1.races.detail", methods={"GET"})
     */
    public function detail(int $raceSlug)
    {
        $race = $this->_getRace($raceSlug);

        $data = $race->toArray();

        return $this->json([
            'success' => true,
            'data' => $data,
            'meta' => [],
        ]);
    }

    /**
     * @Route("/api/v1/races/{raceSlug}/drivers", name="api.v1.races.drivers", methods={"GET"})
     */
    public function drivers(string $raceSlug)
    {
        $race = $this->_getRace($raceSlug);

        /** @var RaceRepository $raceRepository */
        $raceRepository = $this->em->getRepository(RaceDriver::class);
        $raceDrivers = $raceRepository
            ->createQueryBuilder('rd')
            ->where('rd.race = :race')
            ->leftJoin('rd.team', 't')
            ->setParameter('race', $race)
            ->orderBy('t.name')
            ->getQuery()
            ->getResult()
        ;

        $data = [];
        foreach ($raceDrivers as $raceDriver) {
            $data[] = $raceDriver->toArray();
        }

        return $this->json([
            'success' => true,
            'data' => $data,
            'meta' => [],
        ]);
    }

    /**
     * @Route("/api/v1/races/{raceSlug}/drivers", name="api.v1.races.drivers.new", methods={"POST"})
     */
    public function driversNew(string $raceSlug, Request $request)
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        $race = $this->_getRace($raceSlug);

        $raceDriver = new RaceDriver();
        $raceDriver
            ->setRace($race)
        ;

        $formData = $request->request->all();

        $form = $this->createForm(RaceDriverType::class, $raceDriver, [
            'filter_race' => $race,
            'csrf_protection' => false,
        ]);
        $form->submit($formData);

        if (!$form->isValid()) {
            return $this->json([
                'errors' => $this->getFormErrors($form),
            ]);
        }

        $this->em->persist($raceDriver);
        $this->em->flush();

        return $this->json([
            'success' => true,
            'data' => $raceDriver->toArray(),
            'meta' => [],
        ]);
    }

    /**
     * @Route("/api/v1/races/{raceSlug}/drivers/{raceDriverId}", name="api.v1.races.drivers.delete", methods={"DELETE"})
     */
    public function driversDelete(string $raceSlug, int $raceDriverId)
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        $race = $this->_getRace($raceSlug);
        $raceDriver = $this->_getRaceDriver($raceDriverId);

        $this->em->remove($raceDriver);
        $this->em->flush();

        return $this->json([
            'success' => true,
            'data' => [],
            'meta' => [],
        ]);
    }

    /**
     * @Route("/api/v1/races/{raceSlug}/drivers/{raceDriverId}", name="api.v1.races.drivers.edit", methods={"PUT"})
     */
    public function driversEdit(string $raceSlug, int $raceDriverId, Request $request)
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        $race = $this->_getRace($raceSlug);
        $raceDriver = $this->_getRaceDriver($raceDriverId);

        $formData = $request->request->all();
        $formData['team'] = $raceDriver->getTeam()->getId();
        $formData['driver'] = $raceDriver->getDriver()->getId();

        $form = $this->createForm(RaceDriverType::class, $raceDriver, [
            'filter_race' => $race,
            'csrf_protection' => false,
        ]);
        $form->submit($formData);

        if (!$form->isValid()) {
            return $this->json([
                'errors' => $this->getFormErrors($form),
            ]);
        }

        $this->em->persist($raceDriver);
        $this->em->flush();

        return $this->json([
            'success' => true,
            'data' => $raceDriver->toArray(),
            'meta' => [],
        ]);
    }

    /**
     * @Route("/api/v1/races/{raceSlug}/drivers/{raceDriverId}/laps", name="api.v1.races.drivers.laps", methods={"GET"})
     */
    public function driversLaps(string $raceSlug, int $raceDriverId)
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        $race = $this->_getRace($raceSlug);
        $raceDriver = $this->_getRaceDriver($raceDriverId);

        $data = $this->_getRaceDriverLaps($raceDriver);

        return $this->json([
            'success' => true,
            'data' => $data,
            'meta' => [],
        ]);
    }

    /**
     * @Route("/api/v1/races/{raceSlug}/drivers/{raceDriverId}/laps", name="api.v1.races.drivers.laps.edit", methods={"PUT"})
     */
    public function driversLapsEdit(string $raceSlug, int $raceDriverId, Request $request)
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        $race = $this->_getRace($raceSlug);
        $raceDriver = $this->_getRaceDriver($raceDriverId);

        $formData = json_decode($request->request->get('data'), true);
        $errorResponse = $this->_saveRaceDriverLaps($raceDriver, $formData);
        if ($errorResponse) {
            return $this->json([
                'errors' => $errorResponse,
            ]);
        }

        $this->em->flush();

        return $this->json([
            'success' => true,
            'data' => [],
            'meta' => [],
        ]);
    }

    /**
     * @return Race
     */
    private function _getRace(string $raceSlug)
    {
        /** @var RaceRepository $raceRepository */
        $raceRepository = $this->em->getRepository(Race::class);

        $race = $raceRepository->findOneBy([
            'slug' => $raceSlug,
        ]);
        if (!$race) {
            throw $this->createNotFoundException();
        }

        return $race;
    }

    /**
     * @return RaceDriver
     */
    private function _getRaceDriver(int $raceDriverId)
    {
        /** @var RaceDriverRepository $raceDriverRepository */
        $raceDriverRepository = $this->em->getRepository(RaceDriver::class);

        $raceDriver = $raceDriverRepository->findOneBy([
            'id' => $raceDriverId,
        ]);
        if (!$raceDriver) {
            throw $this->createNotFoundException();
        }

        return $raceDriver;
    }

    /**
     * @return array
     */
    private function _getRaceDriverLaps(RaceDriver $raceDriver)
    {
        $race = $raceDriver->getRace();

        // Race Laps
        $emptyRaceDriverRaceLap = new RaceDriverRaceLap();
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
            $raceDriverRaceLapsMap[$raceDriverRaceLap->getLap()] = $raceDriverRaceLap;
        }

        // Race Pit Stops
        $emptyRaceDriverRacePitStop = new RaceDriverRacePitStop();
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
            $raceDriverRacePitStopsMap[$raceDriverRacePitStop->getLap()] = $raceDriverRacePitStop;
        }

        // $laps = $race->getLaps(); // We won't show all if they aren't present
        $maxRaceLapsLap = $raceDriverRaceLapsMap
            ? max(array_keys($raceDriverRaceLapsMap))
            : 0;
        $maxRacePitStopsLap = $raceDriverRacePitStopsMap
            ? max(array_keys($raceDriverRacePitStopsMap))
            : 0;
        $laps = max($maxRaceLapsLap, $maxRacePitStopsLap);

        $data = [];
        for ($lap = 1; $lap <= $laps; ++$lap) {
            // Race Lap
            $raceDriverRaceLap = isset($raceDriverRaceLapsMap[$lap])
                ? $raceDriverRaceLapsMap[$lap]
                : $emptyRaceDriverRaceLap;
            $raceDriverRaceLap->setLap($lap);

            // Race Pit Stop
            $raceDriverRacePitStop = isset($raceDriverRacePitStopsMap[$lap])
                ? $raceDriverRacePitStopsMap[$lap]
                : $emptyRaceDriverRacePitStop;
            $raceDriverRacePitStop->setLap($lap);

            $data[] = [
                'lap' => $lap,
                'race_lap' => $raceDriverRaceLap->toArray(),
                'race_pit_stop' => $raceDriverRacePitStop->toArray(),
                'had_race_pit_stop' => (bool) $raceDriverRacePitStop->getId(),
            ];
        }

        return $data;
    }

    /**
     * @return array returns any errors
     */
    private function _saveRaceDriverLaps(RaceDriver $raceDriver, array $formData)
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
            $hadRacePitStop = $single['had_race_pit_stop'];

            // Race Lap
            /** @var RaceDriverRaceLap $raceDriverRaceLap */
            $raceDriverRaceLap = isset($raceDriverRaceLapsMap[$lap])
                ? $raceDriverRaceLapsMap[$lap]
                : new RaceDriverRaceLap();

            $raceLapForm = $this->createForm(
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
                $errors[$index]['race_lap'] = $this->getFormErrors($raceLapForm);
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

            $racePitStopForm = $this->createForm(
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
                $errors[$index]['race_pit_stop'] = $this->getFormErrors($racePitStopForm);
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
