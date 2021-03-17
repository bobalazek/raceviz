<?php

namespace App\Manager;

use App\Entity\Race;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class RaceManager.
 */
class RaceManager
{
    public function getAppData(Race $race)
    {
        $data = [
            'race' => $race->toArray(),
        ];

        return $data;
    }
}
