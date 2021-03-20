<?php

namespace App\Controller\Api\V1;

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
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        $race = $this->_getRace($raceSlug);

        $data = $race->toArray();

        return $this->json([
            'success' => true,
            'data' => $data,
            'meta' => [],
        ]);
    }
}
