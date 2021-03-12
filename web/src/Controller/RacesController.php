<?php

namespace App\Controller;

use App\Entity\Race;
use App\Repository\RaceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RacesController.
 */
class RacesController extends AbstractController
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
     * @Route("/races", name="races")
     */
    public function index()
    {
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/races/{slug}", name="races.detail")
     */
    public function detail(string $slug)
    {
        /** @var RaceRepository $raceRepository */
        $raceRepository = $this->em->getRepository(Race::class);

        $race = $raceRepository->findOneBy([
            'slug' => $slug,
        ]);
        if (!$race) {
            throw $this->createNotFoundException();
        }

        return $this->render('contents/races/detail.html.twig', [
            'race' => $race,
        ]);
    }

    /**
     * @Route("/races/{slug}/edit", name="races.edit")
     */
    public function edit(string $slug)
    {
        /** @var RaceRepository $raceRepository */
        $raceRepository = $this->em->getRepository(Race::class);

        $race = $raceRepository->findOneBy([
            'slug' => $slug,
        ]);
        if (!$race) {
            throw $this->createNotFoundException();
        }

        return $this->render('contents/races/edit.html.twig', [
            'race' => $race,
        ]);
    }
}
