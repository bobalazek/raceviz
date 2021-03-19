<?php

namespace App\Controller;

use App\Entity\Race;
use App\Entity\Season;
use App\Repository\RaceRepository;
use App\Repository\SeasonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SeasonsController.
 */
class SeasonsController extends AbstractController
{
    /**
     * @var ParameterBagInterface
     */
    private $params;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(
        ParameterBagInterface $params,
        EntityManagerInterface $em
    ) {
        $this->params = $params;
        $this->em = $em;
    }

    /**
     * @Route("/seasons", name="seasons")
     */
    public function index()
    {
        $series = $this->params->get('app.series');

        /** @var SeasonRepository $seasonRepository */
        $seasonRepository = $this->em->getRepository(Season::class);

        $seasonsBySeries = [];
        foreach ($series as $key => $value) {
            $seasons = $seasonRepository->findBy([
                'series' => $key,
            ], [
                'name' => 'DESC',
            ]);
            $seasonsBySeries[] = [
                'name' => $value,
                'seasons' => $seasons,
            ];
        }

        return $this->render('contents/seasons/index.html.twig', [
            'season_by_series' => $seasonsBySeries,
        ]);
    }

    /**
     * @Route("/seasons/{seasonSlug}", name="seasons.detail")
     */
    public function detail(string $seasonSlug)
    {
        /** @var SeasonRepository $seasonRepository */
        $seasonRepository = $this->em->getRepository(Season::class);

        $season = $seasonRepository->findOneBy([
            'slug' => $seasonSlug,
        ]);
        if (!$season) {
            throw $this->createNotFoundException();
        }

        /** @var RaceRepository $raceRepository */
        $raceRepository = $this->em->getRepository(Race::class);

        $races = $raceRepository->findBy([
            'season' => $season,
        ]);

        return $this->render('contents/seasons/detail.html.twig', [
            'season' => $season,
            'races' => $races,
        ]);
    }
}
