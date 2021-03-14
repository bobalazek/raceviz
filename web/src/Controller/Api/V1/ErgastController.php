<?php

namespace App\Controller\Api\V1;

use App\Entity\Race;
use App\Entity\RaceDriver;
use App\Exception\Controller\InvalidParameterException;
use App\Repository\RaceDriverRepository;
use App\Repository\RaceRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

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
    public function raceDriverRaceLaps(string $raceSlug, int $raceDriverId, HttpClientInterface $client)
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

        $ergastSeasonAndRound = $race->getErgastSeasonAndRound();
        if (!$ergastSeasonAndRound) {
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

        $season = $race->getSeason();
        $series = strtolower($season->getSeries());

        $response = $client->request(
            'GET',
            sprintf(
                'http://ergast.com/api/%s/%s/laps.json?limit=2000',
                $series,
                $ergastSeasonAndRound
            )
        );
        $content = $response->toArray();

        if (!isset($content['MRData']['RaceTable']['Races'][0]['Laps'])) {
            throw new InvalidParameterException('No data found for this race.');
        }

        $raceLapsData = $content['MRData']['RaceTable']['Races'][0]['Laps'];

        $data = [];

        foreach ($raceLapsData as $raceLapData) {
            $lap = (int) $raceLapData['number'];
            $allDriverTimes = $raceLapData['Timings'];
            foreach ($allDriverTimes as $allDriverTime) {
                if ($allDriverTime['driverId'] !== $ergastDriverId) {
                    continue;
                }

                $position = (int) $allDriverTime['position'];
                $time = $allDriverTime['time'];

                $data[] = [
                    'lap' => $lap,
                    'position' => $position,
                    'time' => $time,
                    // TODO: also pit stops
                ];
            }
        }

        return $this->json([
            'success' => true,
            'data' => $data,
            'meta' => [],
        ]);
    }
}
