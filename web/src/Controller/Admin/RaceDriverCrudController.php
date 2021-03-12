<?php

namespace App\Controller\Admin;

use App\Admin\Field\RaceResultStatusField;
use App\Admin\Field\TimeWithMillisecondsField;
use App\Admin\Field\TyresField;
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
            ->setSearchFields(['race.name', 'teamVehicle.name', 'driver.firstName', 'driver.lastName'])
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        $race = AssociationField::new('race');
        $teamVehicle = AssociationField::new('teamVehicle');
        $driver = AssociationField::new('driver');
        $raceStartingGridPosition = NumberField::new('raceStartingGridPosition')
            ->setFormTypeOption('html5', true)
        ;
        $raceStartingGridTyres = TyresField::new('raceStartingGridTyres');
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
                $teamVehicle,
                $driver,
            ];
        }

        return [
            $race,
            $teamVehicle,
            $driver,
            FormField::addPanel('Race information'),
            $raceStartingGridPosition,
            $raceStartingGridTyres,
            $raceResultPosition,
            $raceResultPoints,
            $raceResultTime,
            $raceResultLapsBehind,
            $raceResultStatus,
            $raceResultStatusNote,
        ];
    }
}
