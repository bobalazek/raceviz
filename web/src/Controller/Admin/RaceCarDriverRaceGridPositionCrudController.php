<?php

namespace App\Controller\Admin;

use App\Entity\RaceCarDriverRaceGridPosition;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;

class RaceCarDriverRaceGridPositionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RaceCarDriverRaceGridPosition::class;
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
