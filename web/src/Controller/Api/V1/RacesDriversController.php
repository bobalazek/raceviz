<?php

namespace App\Controller\Api\V1;

use App\Entity\Race;
use App\Entity\RaceDriver;
use App\Entity\RaceDriverRaceLap;
use App\Entity\RaceDriverRacePitStop;
use App\Form\Type\RaceDriverType;
use App\Manager\RaceDriverManager;
use App\Repository\RaceDriverRaceLapRepository;
use App\Repository\RaceDriverRacePitStopRepository;
use App\Repository\RaceDriverRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RacesDriversController.
 */
class RacesDriversController extends AbstractApiController
{
    /**
     * @Route("/api/v1/races/{raceSlug}/drivers", name="api.v1.races.drivers", methods={"GET"})
     */
    public function index(string $raceSlug)
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        $race = $this->_getRace($raceSlug);

        /** @var RaceDriverRepository $raceDriverRepository */
        $raceDriverRepository = $this->em->getRepository(RaceDriver::class);
        $raceDrivers = $raceDriverRepository
            ->createQueryBuilder('rd')
            ->where('rd.race = :race')
            ->leftJoin('rd.seasonDriver', 'sd')
            ->leftJoin('sd.team', 't')
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
    public function new(string $raceSlug, Request $request)
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
                'errors' => $this->_getFormErrors($form),
            ], 400);
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
     * @Route("/api/v1/races/{raceSlug}/drivers/prepare-all", name="api.v1.races.drivers.prepare_all", methods={"POST"})
     */
    public function prepareAll(string $raceSlug, RaceDriverManager $raceDriverManager)
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        $race = $this->_getRace($raceSlug);

        $addedRaceDrivers = $raceDriverManager->prepareAll($race);

        $data = [];
        foreach ($addedRaceDrivers as $addedRaceDriver) {
            $data[] = $addedRaceDriver->toArray();
        }

        return $this->json([
            'success' => true,
            'data' => $data,
            'meta' => [],
        ]);
    }

    /**
     * @Route("/api/v1/races/{raceSlug}/drivers/{raceDriverId}", name="api.v1.races.drivers.delete", methods={"DELETE"})
     */
    public function delete(string $raceSlug, int $raceDriverId)
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
    public function edit(string $raceSlug, int $raceDriverId, Request $request)
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        $race = $this->_getRace($raceSlug);
        $raceDriver = $this->_getRaceDriver($raceDriverId);

        $formData = $request->request->all();
        $formData['seasonDriver'] = $raceDriver->getSeasonDriver()->getId();

        if (
            isset($formData['raceDriverRaceStartingGrid']) &&
            $raceDriver->getRaceDriverRaceStartingGrid()
        ) {
            $formData['raceDriverRaceStartingGrid']['raceDriver'] = $raceDriver->getId();
        }

        if (
            isset($formData['raceDriverRaceResult']) &&
            $raceDriver->getRaceDriverRaceResult()
        ) {
            $formData['raceDriverRaceResult']['raceDriver'] = $raceDriver->getId();
        }

        $form = $this->createForm(RaceDriverType::class, $raceDriver, [
            'filter_race' => $race,
            'csrf_protection' => false,
            'with_actual_starting_grid_and_result_data' => true,
        ]);
        $form->submit($formData);

        if (!$form->isValid()) {
            return $this->json([
                'errors' => $this->_getFormErrors($form),
            ], 400);
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
    public function laps(string $raceSlug, int $raceDriverId)
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
    public function lapsEdit(
        string $raceSlug,
        int $raceDriverId,
        Request $request,
        RaceDriverManager $raceDriverManager
    ) {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        $race = $this->_getRace($raceSlug);
        $raceDriver = $this->_getRaceDriver($raceDriverId);

        $formData = json_decode($request->request->get('data'), true);
        $errors = $raceDriverManager->saveLaps($raceDriver, $formData);
        if ($errors) {
            return $this->json([
                'errors' => $errors,
            ], 400);
        }

        return $this->json([
            'success' => true,
            'data' => [],
            'meta' => [],
        ]);
    }

    /**
     * @return array
     */
    private function _getRaceDriverLaps(RaceDriver $raceDriver)
    {
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
}
