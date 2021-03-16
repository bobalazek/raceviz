<?php

namespace App\Manager;

use App\Entity\Race;
use App\Entity\RaceDriver;
use App\Exception\Controller\InvalidParameterException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Class ErgastManager.
 */
class ErgastManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var HttpClientInterface
     */
    private $client;

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var RaceDriverManager
     */
    private $raceDriverManager;

    public function __construct(
        EntityManagerInterface $em,
        HttpClientInterface $client,
        CacheInterface $cache,
        RaceDriverManager $raceDriverManager
    ) {
        $this->em = $em;
        $this->client = $client;
        $this->cache = $cache;
        $this->raceDriverManager = $raceDriverManager;
    }

    /**
     * @return array
     */
    public function getLapsData(
        string $seriesSeasonAndRound,
        string $selectedDriverId = null
    ) {
        // Laps
        $lapsResponseData = $this->_getLapsResponseData($seriesSeasonAndRound);
        if (!isset($lapsResponseData['MRData']['RaceTable']['Races'][0]['Laps'])) {
            throw new InvalidParameterException('No laps data found for this race.');
        }

        $lapsData = $lapsResponseData['MRData']['RaceTable']['Races'][0]['Laps'];

        // Pit stops
        $driverPitStops = [];

        $pitStopsResponseData = $this->_getPitStopsResponseData($seriesSeasonAndRound);
        $pitStopsData = isset($pitStopsResponseData['MRData']['RaceTable']['Races'][0]['PitStops'])
            ? $pitStopsResponseData['MRData']['RaceTable']['Races'][0]['PitStops']
            : [];
        if ($pitStopsData) {
            foreach ($pitStopsData as $pitStopData) {
                $lap = (int) $pitStopData['lap'];
                $driverId = $pitStopData['driverId'];

                if (!isset($driverPitStops[$driverId])) {
                    $driverPitStops[$driverId] = [];
                }

                $driverPitStops[$driverId][$lap] = [
                    'time' => $pitStopData['duration'],
                    'time_of_day' => $pitStopData['time'],
                ];
            }
        }

        // Processing
        $data = [];

        foreach ($lapsData as $raceLapData) {
            $lap = (int) $raceLapData['number'];
            $allDriverTimes = $raceLapData['Timings'];
            foreach ($allDriverTimes as $allDriverTime) {
                $driverId = $allDriverTime['driverId'];
                $position = (int) $allDriverTime['position'];
                $time = $allDriverTime['time'];

                if (!isset($data[$driverId])) {
                    $data[$driverId] = [];
                }

                $raceLap = [
                    'position' => $position,
                    'time' => $time,
                    'time_of_day' => null,
                ];
                $racePitStop = isset($driverPitStops[$driverId][$lap])
                    ? $driverPitStops[$driverId][$lap]
                    : null;

                $data[$driverId][] = [
                    'lap' => $lap,
                    'race_lap' => $raceLap,
                    'race_pit_stop' => $racePitStop,
                    'had_race_pit_stop' => (bool) $racePitStop,
                ];
            }
        }

        if ($selectedDriverId) {
            return $data[$selectedDriverId];
        }

        return $data;
    }

    /**
     * @return array returns any errors
     */
    public function prepareLapsData(Race $race)
    {
        $raceDrivers = $race->getRaceDrivers();
        if (0 === $raceDrivers->count()) {
            return [];
        }

        $ergastSeriesSeasonAndRound = $race->getErgastSeriesSeasonAndRound();
        if (!$ergastSeriesSeasonAndRound) {
            throw new InvalidParameterException('Ergast seriesSeasonAndRound value not set!');
        }

        $errors = [];
        $allDriversLapData = $this->getLapsData($ergastSeriesSeasonAndRound);
        foreach ($raceDrivers as $raceDriver) {
            /** @var RaceDriver $raceDriver */
            $driver = $raceDriver->getDriver();

            $ergastDriverId = $driver->getErgastDriverId();
            if (!isset($allDriversLapData[$ergastDriverId])) {
                continue;
            }

            $raceDriverData = $allDriversLapData[$ergastDriverId];
            $raceDriverErrors = $this->raceDriverManager->saveLaps(
                $raceDriver,
                $raceDriverData
            );

            if ($raceDriverErrors) {
                $errors[$ergastDriverId] = $raceDriverErrors;
            }
        }

        if (!$errors) {
            $this->em->flush();
        }

        return $errors;
    }

    private function _getLapsResponseData(string $seriesSeasonAndRound)
    {
        $key = 'ergast_laps_' . str_replace('/', '_', $seriesSeasonAndRound);

        return $this->cache->get($key, function (ItemInterface $item) use ($seriesSeasonAndRound) {
            $url = sprintf(
                'http://ergast.com/api/%s/laps.json?limit=2000',
                $seriesSeasonAndRound
            );
            $response = $this->client->request(
                'GET',
                $url
            );

            return $response->toArray();
        });
    }

    private function _getPitStopsResponseData(string $seriesSeasonAndRound)
    {
        $key = 'ergast_pitstops_' . str_replace('/', '_', $seriesSeasonAndRound);

        return $this->cache->get($key, function (ItemInterface $item) use ($seriesSeasonAndRound) {
            $url = sprintf(
                'http://ergast.com/api/%s/pitstops.json?limit=2000',
                $seriesSeasonAndRound
            );
            $response = $this->client->request(
                'GET',
                $url
            );

            return $response->toArray();
        });
    }
}
