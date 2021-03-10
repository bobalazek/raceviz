<?php

namespace App\Controller\Admin;

use App\Entity\RaceCarDriverRaceStartingGridPosition;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;

class RaceCarDriverRaceStartingGridPositionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RaceCarDriverRaceStartingGridPosition::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud;
    }

    public function configureFields(string $pageName): iterable
    {
        $position = NumberField::new('position');
        $raceCarDriver = AssociationField::new('raceCarDriver');

        return [
            $position,
            $raceCarDriver,
        ];
    }
}
