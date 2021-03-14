<?php

namespace App\Controller\Api\V1;

use App\Entity\Race;
use App\Entity\RaceDriver;
use App\Entity\RaceDriverRaceLap;
use App\Form\Type\RaceDriverType;
use App\Repository\RaceDriverRaceLapRepository;
use App\Repository\RaceDriverRepository;
use App\Repository\RaceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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

        $raceDriver = new RaceDriver();
        $raceDriver
            ->setRace($race)
        ;

        $formData = $request->request->all();

        $form = $this->createForm(RaceDriverType::class, $raceDriver, [
            'filter_race' => $race,
            'csrf_protection' => false,
        ]);
        $form->submit($formData);

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

        $race = $this->_getRace($raceSlug);
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
     * @Route("/api/v1/races/{raceSlug}/drivers/{raceDriverId}", name="api.v1.races.drivers.edit", methods={"PUT"})
     */
    public function driversEdit(string $raceSlug, int $raceDriverId, Request $request)
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        $race = $this->_getRace($raceSlug);
        $raceDriver = $this->_getRaceDriver($raceDriverId);

        $formData = $request->request->all();
        $formData['team'] = $raceDriver->getTeam()->getId();
        $formData['driver'] = $raceDriver->getDriver()->getId();

        $form = $this->createForm(RaceDriverType::class, $raceDriver, [
            'filter_race' => $race,
            'csrf_protection' => false,
        ]);
        $form->submit($formData);

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
     * @Route("/api/v1/races/{raceSlug}/drivers/{raceDriverId}/laps", name="api.v1.races.drivers.laps", methods={"GET"})
     */
    public function driversLaps(string $raceSlug, int $raceDriverId)
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        $race = $this->_getRace($raceSlug);
        $raceDriver = $this->_getRaceDriver($raceDriverId);

        $data = $this->_getRaceDriverLaps($raceDriver);

        return $this->json([
            'success' => true,
            'data' => $data,
            'meta' => [],
        ]);
    }

    /**
     * @Route("/api/v1/races/{raceSlug}/drivers/{raceDriverId}/laps", name="api.v1.races.drivers.laps.edit", methods={"PUT"})
     */
    public function driversLapsEdit(
        string $raceSlug,
        int $raceDriverId,
        Request $request,
        ValidatorInterface $validator
    ) {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        $race = $this->_getRace($raceSlug);
        $raceDriver = $this->_getRaceDriver($raceDriverId);

        $formData = $request->request->all();
        $errorResponse = $this->_saveRaceDriverLaps($raceDriver, $formData, $validator);
        if ($errorResponse) {
            return $this->json([
                'errors' => $errorResponse,
            ]);
        }

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

    /**
     * @return array
     */
    private function _getRaceDriverLaps(RaceDriver $raceDriver)
    {
        $race = $raceDriver->getRace();

        $raceDriverRaceLapsMap = [];
        /** @var RaceDriverRaceLapRepository $raceDriverRaceLapRepository */
        $raceDriverRaceLapRepository = $this->em->getRepository(RaceDriverRaceLap::class);
        $raceDriverRaceLaps = $raceDriverRaceLapRepository
            ->createQueryBuilder('rdrl')
            ->where('rdrl.raceDriver = :raceDriver')
            ->orderBy('rdrl.lap')
            ->setParameter('raceDriver', $raceDriver)
            ->getQuery()
            ->getResult()
        ;
        foreach ($raceDriverRaceLaps as $raceDriverRaceLap) {
            $raceDriverRaceLapsMap[$raceDriverRaceLap->getLap()] = $raceDriverRaceLap;
        }

        $emptyRaceDriverRaceLap = new RaceDriverRaceLap();

        $data = [];
        for ($lap = 1; $lap <= $race->getLaps(); ++$lap) {
            $raceDriverRaceLap = isset($raceDriverRaceLapsMap[$lap])
                ? $raceDriverRaceLapsMap[$lap]
                : $emptyRaceDriverRaceLap;
            $emptyRaceDriverRaceLap->setLap($lap);

            $data[] = [
                'lap' => $lap,
                'race_lap' => $raceDriverRaceLap->toArray(),
                'had_race_pit_stop' => false,
                'race_pit_stop' => null,
            ];
        }

        return $data;
    }

    /**
     * @return array
     */
    private function _saveRaceDriverLaps(
        RaceDriver $raceDriver,
        array $formData,
        ValidatorInterface $validator
    ) {
        $raceDriverRaceLapsMap = [];
        /** @var RaceDriverRaceLapRepository $raceDriverRaceLapRepository */
        $raceDriverRaceLapRepository = $this->em->getRepository(RaceDriverRaceLap::class);
        $raceDriverRaceLaps = $raceDriverRaceLapRepository
            ->createQueryBuilder('rdrl')
            ->where('rdrl.raceDriver = :raceDriver')
            ->orderBy('rdrl.lap')
            ->setParameter('raceDriver', $raceDriver)
            ->getQuery()
            ->getResult()
        ;
        foreach ($raceDriverRaceLaps as $raceDriverRaceLap) {
            $raceDriverRaceLapsMap[$raceDriverRaceLap->getLap()] = $raceDriverRaceLap;
        }

        $errors = [];

        foreach ($formData as $index => $single) {
            $raceLap = $single['race_lap'];
            $lap = (int) $raceLap['lap'];
            $position = (int) $raceLap['position'];
            $time = $raceLap['time'];
            $timeOfDay = $raceLap['time_of_day'];
            $tyres = $raceLap['tyres'];

            $raceDriverRaceLap = isset($raceDriverRaceLapsMap[$lap])
                ? $raceDriverRaceLapsMap[$lap]
                : new RaceDriverRaceLap();

            $raceDriverRaceLap
                ->setLap($lap)
                ->setPosition($position)
                ->setTime($time)
                ->setTimeOfDay($timeOfDay)
                ->setTyres($tyres)
            ;

            $raceLapValidatorErrors = $validator->validate($raceDriverRaceLap);
            if (count($raceLapValidatorErrors) > 0) {
                $raceLapErrors = [];
                foreach ($raceLapValidatorErrors as $validatorError) {
                    $singleErrors[] = $validatorError->getMessage();
                }
                $errors[$index]['race_lap'] = $raceLapErrors;
            }

            $this->em->persist($raceDriverRaceLap);
        }

        return $errors;
    }
}
