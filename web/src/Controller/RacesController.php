<?php

namespace App\Controller;

use App\Entity\Race;
use App\Manager\RaceManager;
use App\Repository\RaceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RacesController.
 */
class RacesController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(
        EntityManagerInterface $em
    ) {
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
     * @Route("/races/{raceSlug}", name="races.detail")
     */
    public function detail(string $raceSlug, RaceManager $raceManager)
    {
        $race = $this->_getRace($raceSlug);

        $appData = $raceManager->getAppData($race);

        return $this->render('contents/races/detail.html.twig', [
            'race' => $race,
            'app_data' => $appData,
        ]);
    }

    /**
     * @Route("/races/{raceSlug}/edit", name="races.edit")
     */
    public function edit(string $raceSlug)
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('home');
        }

        $race = $this->_getRace($raceSlug);

        return $this->render('contents/races/edit.html.twig', [
            'race' => $race,
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
}
