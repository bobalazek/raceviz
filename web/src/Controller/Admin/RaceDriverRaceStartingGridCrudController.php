<?php

namespace App\Controller\Admin;

use App\Admin\Field\TimeWithMillisecondsField;
use App\Admin\Field\TyresField;
use App\Entity\RaceDriverRaceStartingGrid;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;

class RaceDriverRaceStartingGridCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RaceDriverRaceStartingGrid::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $raceDriver = AssociationField::new('raceDriver');
        $position = NumberField::new('position')
            ->setFormTypeOption('html5', true)
        ;
        $time = TimeWithMillisecondsField::new('time');
        $tyres = TyresField::new('tyres');

        return [
            $raceDriver,
            $position,
            $time,
            $tyres,
        ];
    }
}
