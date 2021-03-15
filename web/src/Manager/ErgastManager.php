<?php

namespace App\Manager;

use App\Entity\User;
use App\Entity\UserAction;
use App\Exception\Controller\InvalidParameterException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Class ErgastManager.
 */
class ErgastManager
{
    /**
     * @var HttpClientInterface
     */
    private $client;

    /**
     * @var CacheInterface
     */
    private $cache;

    public function __construct(
        HttpClientInterface $client,
        CacheInterface $cache
    ) {
        $this->client = $client;
        $this->cache = $cache;
    }

    /**
     * @return array
     */
    public function getLapsData(
        string $seriesSeasonAndRound,
        string $driverId
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
                if ($pitStopData['driverId'] !== $driverId) {
                    continue;
                }

                $driverPitStops[$lap] = [
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
                if ($allDriverTime['driverId'] !== $driverId) {
                    continue;
                }

                $position = (int) $allDriverTime['position'];
                $time = $allDriverTime['time'];

                $data[] = [
                    'lap' => $lap,
                    'race_lap' => [
                        'position' => $position,
                        'time' => $time,
                        'time_of_day' => null,
                    ],
                    'race_pit_stop' => isset($driverPitStops[$lap])
                        ? $driverPitStops[$lap]
                        : null,
                ];
            }
        }

        return $data;
    }

    private function _getLapsResponseData(string $seriesSeasonAndRound)
    {
        $key = 'ergast_laps_' . str_replace('/', '_', $seriesSeasonAndRound);
        return $this->cache->get($key, function(ItemInterface $item) use ($seriesSeasonAndRound) {
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
        return $this->cache->get($key, function(ItemInterface $item) use ($seriesSeasonAndRound) {
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
