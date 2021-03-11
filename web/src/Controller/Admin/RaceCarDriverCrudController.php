<?php

namespace App\Controller\Admin;

use App\Admin\Field\TimeWithMillisecondsField;
use App\Entity\RaceCarDriver;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

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
        $raceResultPosition = NumberField::new('raceResultPosition');
        $raceResultPoints = NumberField::new('raceResultPoints');
        $raceResultTime = TimeWithMillisecondsField::new('raceResultTime'); // TODO: allow hours
        $raceResultLapsBehind = NumberField::new('raceResultLapsBehind');
        $raceResultStatus = TextField::new('raceResultStatus');
        $raceResultStatusNote = TextareaField::new('raceResultStatusNote');

        return [
            $race,
            $car,
            $driver,
            $raceStartingGridPosition,
            $raceResultPosition,
            $raceResultPoints,
            $raceResultTime,
            $raceResultLapsBehind,
            $raceResultStatus,
            $raceResultStatusNote,
        ];
    }
}
