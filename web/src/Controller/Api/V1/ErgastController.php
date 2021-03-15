<?php

namespace App\Controller\Api\V1;

use App\Entity\Race;
use App\Entity\RaceDriver;
use App\Exception\Controller\InvalidParameterException;
use App\Manager\ErgastManager;
use App\Repository\RaceDriverRepository;
use App\Repository\RaceRepository;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ErgastController.
 */
class ErgastController extends AbstractApiController
{
    /**
     * @Route("/api/v1/ergast", name="api.v1.ergast", methods={"GET"})
     */
    public function index()
    {
        return $this->json([
            'success' => true,
            'data' => [],
            'meta' => [],
        ]);
    }

    /**
     * @Route("/api/v1/ergast/{raceSlug}/{raceDriverId}/laps", name="api.v1.ergast.race_driver_race_laps", methods={"GET"})
     */
    public function raceDriverRaceLaps(string $raceSlug, int $raceDriverId, ErgastManager $ergastManager)
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        /** @var RaceRepository $raceRepository */
        $raceRepository = $this->em->getRepository(Race::class);
        $race = $raceRepository->findOneBy([
            'slug' => $raceSlug,
        ]);
        if (!$race) {
            throw $this->createNotFoundException();
        }

        $ergastSeriesSeasonAndRound = $race->getErgastSeriesSeasonAndRound();
        if (!$ergastSeriesSeasonAndRound) {
            throw new InvalidParameterException('This race has no ergast season and round set!');
        }

        /** @var RaceDriverRepository $raceDriverRepository */
        $raceDriverRepository = $this->em->getRepository(RaceDriver::class);
        $raceDriver = $raceDriverRepository->findOneBy([
            'id' => $raceDriverId,
        ]);
        if (!$raceDriver) {
            throw $this->createNotFoundException();
        }

        $driver = $raceDriver->getDriver();
        $ergastDriverId = $driver->getErgastDriverId();
        if (!$ergastDriverId) {
            throw new InvalidParameterException('This driver has no ergast driver id set!');
        }

        $data = $ergastManager->getLapsData(
            $ergastSeriesSeasonAndRound,
            $ergastDriverId
        );

        return $this->json([
            'success' => true,
            'data' => $data,
            'meta' => [],
        ]);
    }
}
