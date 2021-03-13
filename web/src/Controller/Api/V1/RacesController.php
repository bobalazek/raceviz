<?php

namespace App\Controller\Api\V1;

use App\Entity\Race;
use App\Entity\RaceDriver;
use App\Form\Type\RaceDriverType;
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
        $data = $request->request->all();

        $raceDriver = new RaceDriver();
        $raceDriver
            ->setRace($race)
        ;

        $form = $this->createForm(RaceDriverType::class, $raceDriver, [
            'filter_race' => $race,
            'csrf_protection' => false,
        ]);
        $form->submit($data);

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

        $this->_getRace($raceSlug);

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
}
