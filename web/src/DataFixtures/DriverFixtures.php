<?php

namespace App\DataFixtures;

use App\Entity\Driver;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Class DriverFixtures.
 */
class DriverFixtures extends Fixture
{
    /**
     * @var array
     */
    private $entries;

    public function __construct()
    {
        $this->entries = include __DIR__ . '/data/drivers.php';
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->entries as $entry) {
            $entity = new Driver();
            $entity
                ->setFirstName($entry['first_name'])
                ->setLastName($entry['last_name'])
                ->setSlug($entry['slug'])
                ->setCountryCode($entry['country_code'])
                ->setUrl($entry['url'])
                ->setErgastDriverId(isset($entry['ergast_driver_id'])
                    ? $entry['ergast_driver_id']
                    : null)
            ;

            $manager->persist($entity);

            $manager->flush();
        }
    }
}
