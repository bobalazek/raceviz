<?php

namespace App\Manager;

use App\Entity\Race;

/**
 * Class RaceManager.
 */
class RaceManager
{
    public function getAppData(Race $race)
    {
        return [
            'race' => $race->toArray(),
        ];
    }
}
