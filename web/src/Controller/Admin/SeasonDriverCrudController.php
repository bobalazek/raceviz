<?php

namespace App\Controller\Admin;

use App\Entity\SeasonDriver;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;

class SeasonDriverCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SeasonDriver::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['number', 'season.name', 'driver.firstName', 'driver.lastName', 'team.name'])
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        $season = AssociationField::new('season');
        $driver = AssociationField::new('driver');
        $team = AssociationField::new('team');
        $number = NumberField::new('number');

        return [
            $season,
            $driver,
            $team,
            $number,
        ];
    }
}
