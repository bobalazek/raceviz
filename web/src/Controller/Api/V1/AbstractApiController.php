<?php

namespace App\Controller\Api\V1;

use App\Entity\Race;
use App\Entity\RaceDriver;
use App\Entity\RaceIncident;
use App\Entity\RaceIncidentRaceDriver;
use App\Manager\ErrorManager;
use App\Repository\RaceDriverRepository;
use App\Repository\RaceIncidentRaceDriverRepository;
use App\Repository\RaceIncidentRepository;
use App\Repository\RaceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class AbstractApiController.
 */
class AbstractApiController extends AbstractController
{
    /**
     * @var ParameterBagInterface
     */
    protected $params;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var UrlGeneratorInterface
     */
    protected $router;

    /**
     * @var ErrorManager
     */
    protected $errorManager;

    public function __construct(
        ParameterBagInterface $params,
        EntityManagerInterface $em,
        UrlGeneratorInterface $router,
        ErrorManager $errorManager
    ) {
        $this->params = $params;
        $this->em = $em;
        $this->router = $router;
        $this->errorManager = $errorManager;
    }

    protected function _getFormErrors(FormInterface $form)
    {
        return $this->errorManager->getFormErrors($form);
    }

    /**
     * @return Race
     */
    protected function _getRace(string $raceSlug)
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
    protected function _getRaceDriver(int $raceDriverId)
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
     * @return RaceIncident
     */
    protected function _getRaceIncident(int $raceIncidentId)
    {
        /** @var RaceIncidentRepository $raceIncidentRepository */
        $raceIncidentRepository = $this->em->getRepository(RaceIncident::class);
        $raceIncident = $raceIncidentRepository->findOneBy([
            'id' => $raceIncidentId,
        ]);
        if (!$raceIncident) {
            throw $this->createNotFoundException();
        }

        return $raceIncident;
    }

    /**
     * @return RaceIncidentRaceDriver
     */
    protected function _getRaceIncidentRaceDriver(int $raceIncidentRaceDriverId)
    {
        /** @var RaceIncidentRaceDriverRepository $raceIncidentRaceDriverRepository */
        $raceIncidentRaceDriverRepository = $this->em->getRepository(RaceIncidentRaceDriver::class);
        $raceIncidentRaceDriver = $raceIncidentRaceDriverRepository->findOneBy([
            'id' => $raceIncidentRaceDriverId,
        ]);
        if (!$raceIncidentRaceDriver) {
            throw $this->createNotFoundException();
        }

        return $raceIncidentRaceDriver;
    }
}
