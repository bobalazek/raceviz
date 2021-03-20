<?php

namespace App\Manager;

use App\Entity\Race;
use App\Entity\RaceDriver;
use App\Repository\RaceDriverRepository;
use Doctrine\ORM\EntityManagerInterface;
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

    public function __construct(
        EntityManagerInterface $em,
        UploaderHelper $uploaderHelper
    ) {
        $this->em = $em;
        $this->uploaderHelper = $uploaderHelper;
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

    private function _getVehicleModelUrl(RaceDriver $raceDriver)
    {
        $file = $this->uploaderHelper->asset($raceDriver, 'vehicle');
        if ($file) {
            return $this->request->getUriForPath($file);
        }

        $
        {$file} = $this->uploaderHelper->asset($raceDriver, 'vehicle');
        if ($file) {
            return $this->request->getUriForPath($file);
        }

        return null;
    }
}
