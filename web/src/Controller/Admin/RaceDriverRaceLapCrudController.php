<?php

namespace App\Controller\Admin;

use App\Admin\Field\TimeWithMillisecondsField;
use App\Admin\Field\TyresField;
use App\Entity\RaceDriverRaceLap;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;

class RaceDriverRaceLapCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RaceDriverRaceLap::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $raceDriver = AssociationField::new('raceDriver');
        $lap = NumberField::new('lap')
            ->setFormTypeOption('html5', true)
        ;
        $position = NumberField::new('position')
            ->setFormTypeOption('html5', true)
        ;
        $time = TimeWithMillisecondsField::new('time');
        $timeOfDay = TimeField::new('timeOfDay')
            ->setFormTypeOption('with_seconds', true)
        ;
        $tyres = TyresField::new('tyres');

        return [
            $raceDriver,
            $lap,
            $position,
            $time,
            $timeOfDay,
            $tyres,
        ];
    }
}
