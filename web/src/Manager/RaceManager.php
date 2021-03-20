<?php

namespace App\Manager;

use App\Entity\Race;
use App\Entity\RaceDriver;
use App\Entity\SeasonTeam;
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
            $raceDriverData = $raceDriver->toArray();

            $raceDriverData['vehicle_model_url'] = $this->_getVehicleModelUrl($raceDriver);

            $data['race_drivers'][] = $raceDriverData;
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
