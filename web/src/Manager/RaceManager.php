<?php

namespace App\Manager;

use App\Entity\Race;
use App\Entity\RaceDriver;
use App\Entity\RaceDriverRaceLap;
use App\Entity\SeasonTeam;
use App\Repository\RaceDriverRaceLapRepository;
use App\Repository\RaceDriverRepository;
use App\Repository\SeasonTeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

/**
 * Class RaceManager.
 */
class RaceManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var UploaderHelper
     */
    private $uploaderHelper;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var Request
     */
    private $request;

    public function __construct(
        EntityManagerInterface $em,
        UploaderHelper $uploaderHelper,
        RequestStack $requestStack
    ) {
        $this->em = $em;
        $this->uploaderHelper = $uploaderHelper;
        $this->requestStack = $requestStack;

        $this->request = $this->requestStack->getCurrentRequest();
    }

    public function getAppData(Race $race)
    {
        $data = [
            'race' => $race->toArray(),
            'race_drivers' => [],
        ];

        /** @var RaceDriverRaceLapRepository $raceDriverRaceLapRepository */
        $raceDriverRaceLapRepository = $this->em->getRepository(RaceDriverRaceLap::class);

        /** @var RaceDriverRepository $raceDriverRepository */
        $raceDriverRepository = $this->em->getRepository(RaceDriver::class);
        $raceDrivers = $raceDriverRepository
            ->createQueryBuilder('rd')
            ->leftJoin('rd.raceDriverRaceStartingGrid', 'rdrsg')
            ->where('rd.race = :race')
            ->orderBy('rdrsg.position', 'DESC')
            ->setParameter('race', $race)
            ->getQuery()
            ->getResult()
        ;
        foreach ($raceDrivers as $raceDriver) {
            /** @var RaceDriver $raceDriver */
            $raceDriverData = $raceDriver->toArray();

            $raceDriverData['vehicle_model_url'] = $this->_getVehicleModelUrl($raceDriver);

            $data['race_drivers'][] = $raceDriverData;

            // TODO: DO NOT DO QUERIES IN A LOOP!!!

            $id = $raceDriver->getId();
            $data['race_driver_laps'][$id] = [];

            $raceDriverRaceLaps = $raceDriverRaceLapRepository->findBy([
                'raceDriver' => $raceDriver,
            ]);
            foreach ($raceDriverRaceLaps as $raceDriverRaceLap) {
                $lap = $raceDriverRaceLap->getLap();
                $timeDuration = $raceDriverRaceLap->getTimeDuration()->format('H:i:s:u');
                $timeDurationExploded = explode(':', $timeDuration);

                $timeInMilliseconds = (
                    ((int) $timeDurationExploded[0] * 3600 * 1000) +
                    ((int) $timeDurationExploded[1] * 60 * 1000) +
                    ((int) $timeDurationExploded[2] * 1000) +
                    ((int) $timeDurationExploded[3] / 1000)
                );

                $data['race_driver_laps'][$id][$lap] = [
                    'position' => $raceDriverRaceLap->getPosition(),
                    'time' => $timeInMilliseconds,
                ];
            }
        }

        return $data;
    }

    /**
     * TODO: optimize this whole method.
     *
     * @return string|null
     */
    private function _getVehicleModelUrl(RaceDriver $raceDriver)
    {
        $seasonDriver = $raceDriver->getSeasonDriver();
        if ($seasonDriver->getVehicle()) {
            $file = $this->uploaderHelper->asset($seasonDriver->getVehicle(), 'file');
            if ($file) {
                return $this->request->getUriForPath($file);
            }
        }

        $season = $seasonDriver->getSeason();
        $team = $seasonDriver->getTeam();

        /** @var SeasonTeamRepository $seasonTeamRepository */
        $seasonTeamRepository = $this->em->getRepository(SeasonTeam::class);

        $seasonTeam = $seasonTeamRepository->findOneBy([
            'season' => $season,
            'team' => $team,
        ]);
        if (
            $seasonTeam &&
            $seasonTeam->getVehicle()
        ) {
            $file = $this->uploaderHelper->asset($seasonTeam->getVehicle(), 'file');
            if ($file) {
                return $this->request->getUriForPath($file);
            }
        }

        if ($season->getGenericVehicle()) {
            $file = $this->uploaderHelper->asset($season->getGenericVehicle(), 'file');
            if ($file) {
                return $this->request->getUriForPath($file);
            }
        }

        return null;
    }
}
