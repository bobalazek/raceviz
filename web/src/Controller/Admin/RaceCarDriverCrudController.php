<?php

namespace App\Controller\Admin;

use App\Admin\Field\RaceResultStatusField;
use App\Admin\Field\TimeWithMillisecondsField;
use App\Entity\RaceCarDriver;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

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
        $raceStartingGridPosition = NumberField::new('raceStartingGridPosition')
            ->setFormTypeOption('html5', true)
        ;
        $raceResultPosition = NumberField::new('raceResultPosition')
            ->setFormTypeOption('html5', true)
        ;
        $raceResultPoints = NumberField::new('raceResultPoints')
            ->setFormTypeOption('html5', true)
        ;
        $raceResultTime = TimeWithMillisecondsField::new('raceResultTime');
        $raceResultLapsBehind = NumberField::new('raceResultLapsBehind');
        $raceResultStatus = RaceResultStatusField::new('raceResultStatus');
        $raceResultStatusNote = TextareaField::new('raceResultStatusNote');

        if (Crud::PAGE_INDEX === $pageName) {
            return [
                $race,
                $car,
                $driver,
            ];
        }

        return [
            $race,
            $car,
            $driver,
            FormField::addPanel('Race information'),
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
