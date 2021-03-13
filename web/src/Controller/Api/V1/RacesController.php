<?php

namespace App\Controller\Api\V1;

use App\Entity\Race;
use App\Entity\RaceDriver;
use App\Form\Type\RaceDriverType;
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
    public function driversNew(int $slug, Request $request)
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        $race = $this->_getRace($slug);

        $raceDriver = new RaceDriver();
        $raceDriver
            ->setRace($race)
        ;

        $form = $this->createForm(RaceDriverType::class, $raceDriver, [
            'filter_race' => $race,
        ]);
        $form->submit($request->request->all());

        if (!$form->isValid()) {
            // TODO
        }

        return $this->json([
            'success' => true,
            'data' => [],
            'meta' => [],
        ]);
    }

    /**
     * @Route("/api/v1/races/{slug}/drivers", name="api.v1.races.drivers.delete", methods={"DELETE"})
     */
    public function driversDelete(int $slug)
    {
        if ($this->isGranted('ROLE_ADMIN')) {
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
