<?php

namespace App\Controller\Admin;

use App\Admin\Field\RaceResultStatusField;
use App\Admin\Field\TimeWithMillisecondsField;
use App\Entity\RaceDriver;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

class RaceDriverCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RaceDriver::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['race.name', 'vehicle.name', 'driver.firstName', 'driver.lastName'])
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        $race = AssociationField::new('race');
        $vehicle = AssociationField::new('vehicle');
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
                $vehicle,
                $driver,
            ];
        }

        return [
            $race,
            $vehicle,
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
