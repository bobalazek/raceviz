<?php

namespace App\Controller\Admin;

use App\Entity\TeamVehicle;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TeamVehicleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TeamVehicle::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['name', 'number'])
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        $name = TextField::new('name');
        $slug = TextField::new('slug');
        $number = NumberField::new('number');
        $team = AssociationField::new('team');
        $driver = AssociationField::new('driver');

        return [
            $name,
            $slug,
            $number,
            $team,
            $driver,
        ];
    }
}
