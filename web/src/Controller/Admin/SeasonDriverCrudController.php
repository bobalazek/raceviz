<?php

namespace App\Controller\Admin;

use App\Entity\SeasonDriver;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SeasonDriverCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SeasonDriver::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['number', 'code', 'season.name', 'driver.firstName', 'driver.lastName', 'team.name'])
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        $number = NumberField::new('number');
        $code = TextField::new('code');
        $season = AssociationField::new('season');
        $driver = AssociationField::new('driver');
        $team = AssociationField::new('team');

        return [
            $number,
            $code,
            $season,
            $driver,
            $team,
        ];
    }
}
