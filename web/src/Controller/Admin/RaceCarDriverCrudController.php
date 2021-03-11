<?php

namespace App\Controller\Admin;

use App\Entity\RaceCarDriver;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;

class RaceCarDriverCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RaceCarDriver::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['race.name', 'car.name', 'driver.firstName', 'driver.lastName'])
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        $race = AssociationField::new('race');
        $car = AssociationField::new('car');
        $driver = AssociationField::new('driver');
        $raceStartingGridPosition = NumberField::new('raceStartingGridPosition');

        return [
            $race,
            $car,
            $driver,
            $raceStartingGridPosition,
        ];
    }
}
