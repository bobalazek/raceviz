<?php

namespace App\Controller\Api\V1;

use App\Entity\Season;
use App\Entity\SeasonDriver;
use App\Entity\SeasonTeam;
use App\Repository\SeasonDriverRepository;
use App\Repository\SeasonRepository;
use App\Repository\SeasonTeamRepository;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SeasonsController.
 */
class SeasonsController extends AbstractApiController
{
    /**
     * @Route("/api/v1/seasons", name="api.v1.seasons", methods={"GET"})
     */
    public function index()
    {
        /** @var SeasonRepository $seasonRepository */
        $seasonRepository = $this->em->getRepository(Season::class);
        $seasons = $seasonRepository->findAll();

        $data = [];
        foreach ($seasons as $season) {
            $data[] = $season->toArray();
        }

        return $this->json([
            'success' => true,
            'data' => $data,
            'meta' => [],
        ]);
    }

    /**
     * @Route("/api/v1/seasons/{seasonSlug}", name="api.v1.seasons.detail", methods={"GET"})
     */
    public function detail(string $seasonSlug)
    {
        $season = $this->_getSeason($seasonSlug);

        $data = $season->toArray();

        return $this->json([
            'success' => true,
            'data' => $data,
            'meta' => [],
        ]);
    }

    /**
     * @Route("/api/v1/seasons/{seasonSlug}/drivers", name="api.v1.seasons.drivers", methods={"GET"})
     */
    public function drivers(string $seasonSlug)
    {
        $season = $this->_getSeason($seasonSlug);

        /** @var SeasonDriverRepository $seasonDriverRepository */
        $seasonDriverRepository = $this->em->getRepository(SeasonDriver::class);
        $seasonDrivers = $seasonDriverRepository->findBy([
            'season' => $season,
        ]);

        $data = [];
        foreach ($seasonDrivers as $seasonDriver) {
            $data[] = $seasonDriver->toArray();
        }

        return $this->json([
            'success' => true,
            'data' => $data,
            'meta' => [],
        ]);
    }

    /**
     * @Route("/api/v1/seasons/{seasonSlug}/teams", name="api.v1.seasons.teams", methods={"GET"})
     */
    public function teams(string $seasonSlug)
    {
        $season = $this->_getSeason($seasonSlug);

        /** @var SeasonTeamRepository $seasonTeamRepository */
        $seasonTeamRepository = $this->em->getRepository(SeasonTeam::class);
        $seasonTeams = $seasonTeamRepository->findBy([
            'season' => $season,
        ]);

        $data = [];
        foreach ($seasonTeams as $seasonTeam) {
            $data[] = $seasonTeam->toArray();
        }

        return $this->json([
            'success' => true,
            'data' => $data,
            'meta' => [],
        ]);
    }

    /**
     * @return Season
     */
    private function _getSeason(string $seasonSlug)
    {
        /** @var SeasonRepository $seasonRepository */
        $seasonRepository = $this->em->getRepository(Season::class);
        $season = $seasonRepository->findOneBy([
            'slug' => $seasonSlug,
        ]);
        if (!$season) {
            throw $this->createNotFoundException();
        }

        return $season;
    }
}
