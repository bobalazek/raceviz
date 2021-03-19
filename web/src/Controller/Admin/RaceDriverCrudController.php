<?php

namespace App\Controller\Admin;

use App\Entity\RaceDriver;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class RaceDriverCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RaceDriver::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['race.name', 'team.name', 'driver.firstName', 'driver.lastName'])
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        $race = AssociationField::new('race');
        $team = AssociationField::new('team');
        $driver = AssociationField::new('driver');
        $raceDriverRaceStartingGrid = AssociationField::new('raceDriverRaceStartingGrid');
        $raceDriverRaceResult = AssociationField::new('raceDriverRaceResult');

        if (Crud::PAGE_INDEX === $pageName) {
            return [
                $race,
                $team,
                $driver,
            ];
        }

        return [
            $race,
            $team,
            $driver,
            $raceDriverRaceStartingGrid,
            $raceDriverRaceResult,
        ];
    }
}
