<?php

namespace App\Controller\Admin;

use App\Admin\Field\TimeWithMillisecondsField;
use App\Entity\RaceCarDriverRaceLap;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;

class RaceCarDriverRaceLapCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RaceCarDriverRaceLap::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $raceCarDriver = AssociationField::new('raceCarDriver');
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

        return [
            $raceCarDriver,
            $lap,
            $position,
            $time,
            $timeOfDay,
        ];
    }
}
