<?php

namespace App\Controller\Api\V1;

use App\Entity\Race;
use App\Entity\RaceDriver;
use App\Form\Type\RaceDriverType;
use App\Repository\RaceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RacesController.
 */
class RacesController extends AbstractApiController
{
    /**
     * @Route("/api/v1/races/{slug}", name="api.v1.races.detail", methods={"GET"})
     */
    public function detail(int $slug)
    {
        $race = $this->_getRace($slug);

        $data = $race->toArray();

        return $this->json([
            'success' => true,
            'data' => $data,
            'meta' => [],
        ]);
    }

    /**
     * @Route("/api/v1/races/{slug}/drivers", name="api.v1.races.drivers", methods={"GET"})
     */
    public function drivers(string $slug)
    {
        $race = $this->_getRace($slug);

        /** @var RaceRepository $raceRepository */
        $raceRepository = $this->em->getRepository(RaceDriver::class);
        $raceDrivers = $raceRepository->findBy([
            'race' => $race,
        ]);

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
     * @Route("/api/v1/races/{slug}/drivers", name="api.v1.races.drivers.new", methods={"POST"})
     */
    public function driversNew(string $slug, Request $request)
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        $race = $this->_getRace($slug);
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
     * @Route("/api/v1/races/{slug}/drivers", name="api.v1.races.drivers.delete", methods={"DELETE"})
     */
    public function driversDelete(int $slug)
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        $race = $this->_getRace($slug);

        // TODO

        return $this->json([
            'success' => true,
            'data' => [],
            'meta' => [],
        ]);
    }

    /**
     * @return Race
     */
    private function _getRace(string $slug)
    {
        /** @var RaceRepository $raceRepository */
        $raceRepository = $this->em->getRepository(Race::class);

        $race = $raceRepository->findOneBy([
            'slug' => $slug,
        ]);
        if (!$race) {
            throw $this->createNotFoundException();
        }

        return $race;
    }
}
