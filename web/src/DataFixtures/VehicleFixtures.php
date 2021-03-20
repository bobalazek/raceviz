<?php

namespace App\DataFixtures;

use App\Entity\Vehicle;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class VehicleFixtures.
 */
class VehicleFixtures extends Fixture
{
    /**
     * @var array
     */
    private $entries;

    public function __construct()
    {
        $this->entries = include __DIR__ . '/data/vehicles.php';
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->entries as $entry) {
            $file = new UploadedFile(
                __DIR__ . DIRECTORY_SEPARATOR .
                'data' . DIRECTORY_SEPARATOR .
                'vehicles' . DIRECTORY_SEPARATOR .
                $entry['file'],
                $entry['file']
            );

            $entity = new Vehicle();
            $entity
                ->setName($entry['name'])
                ->setSlug($entry['slug'])
                ->setType($entry['type'])
                ->setFile($file)
            ;

            $manager->persist($entity);

            $manager->flush();
        }
    }
}
