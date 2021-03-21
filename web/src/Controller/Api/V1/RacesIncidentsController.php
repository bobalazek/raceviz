<?php

namespace App\Controller\Api\V1;

use App\Entity\RaceIncident;
use App\Form\Type\RaceIncidentType;
use App\Repository\RaceIncidentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RacesIncidentsController.
 */
class RacesIncidentsController extends AbstractApiController
{
    /**
     * @Route("/api/v1/races/{raceSlug}/incidents", name="api.v1.races.incidents", methods={"GET"})
     */
    public function index(int $raceSlug)
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        $race = $this->_getRace($raceSlug);

        /** @var RaceIncidentRepository $raceIncidentRepository */
        $raceIncidentRepository = $this->em->getRepository(RaceIncident::class);
        $raceIncidents = $raceIncidentRepository
            ->createQueryBuilder('ri')
            ->where('ri.race = :race')
            ->setParameter('race', $race)
            ->orderBy('ri.lap')
            ->getQuery()
            ->getResult()
        ;

        $data = [];
        foreach ($raceIncidents as $raceIncident) {
            $data[] = $raceIncident->toArray();
        }

        return $this->json([
            'success' => true,
            'data' => $data,
            'meta' => [],
        ]);
    }

    /**
     * @Route("/api/v1/races/{raceSlug}/incidents", name="api.v1.races.incidents.new", methods={"POST"})
     */
    public function new(string $raceSlug, Request $request)
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        $race = $this->_getRace($raceSlug);

        $raceIncident = new RaceIncident();
        $raceIncident
            ->setRace($race)
        ;

        $formData = $request->request->all();

        $form = $this->createForm(RaceIncidentType::class, $raceIncident, [
            'filter_race' => $race,
            'csrf_protection' => false,
        ]);
        $form->submit($formData);

        if (!$form->isValid()) {
            return $this->json([
                'errors' => $this->_getFormErrors($form),
            ], 400);
        }

        $this->em->persist($raceIncident);
        $this->em->flush();

        return $this->json([
            'success' => true,
            'data' => $raceIncident->toArray(),
            'meta' => [],
        ]);
    }

    /**
     * @Route("/api/v1/races/{raceSlug}/incidents/{raceIncidentId}", name="api.v1.races.incidents.edit", methods={"PUT"})
     */
    public function edit(string $raceSlug, int $raceIncidentId, Request $request)
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        $race = $this->_getRace($raceSlug);
        $raceIncident = $this->_getRaceIncident($raceIncidentId);

        $formData = $request->request->all();

        $form = $this->createForm(RaceIncidentType::class, $raceIncident, [
            'filter_race' => $race,
            'csrf_protection' => false,
        ]);
        $form->submit($formData);

        if (!$form->isValid()) {
            return $this->json([
                'errors' => $this->_getFormErrors($form),
            ], 400);
        }

        $this->em->persist($raceIncident);
        $this->em->flush();

        return $this->json([
            'success' => true,
            'data' => $raceIncident->toArray(),
            'meta' => [],
        ]);
    }

    /**
     * @Route("/api/v1/races/{raceSlug}/incidents/{raceIncidentId}", name="api.v1.races.incidents.delete", methods={"DELETE"})
     */
    public function delete(string $raceSlug, int $raceIncidentId)
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        $race = $this->_getRace($raceSlug);
        $raceIncident = $this->_getRaceIncident($raceIncidentId);

        $this->em->remove($raceIncident);
        $this->em->flush();

        return $this->json([
            'success' => true,
            'data' => [],
            'meta' => [],
        ]);
    }
}
