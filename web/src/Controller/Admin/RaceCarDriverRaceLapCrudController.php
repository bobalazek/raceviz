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
        $lap = NumberField::new('lap');
        $position = NumberField::new('position');
        $time = TimeWithMillisecondsField::new('time')
            ->setHelp('This MUST be the following format: "minutes:seconds.miliseconds" (1:40.950)')
        ;
        $timeOfDay = TimeField::new('timeOfDay')
            ->setFormTypeOption('with_seconds', true)
        ;
        $raceCarDriver = AssociationField::new('raceCarDriver');

        return [
            $lap,
            $position,
            $time,
            $timeOfDay,
            $raceCarDriver,
        ];
    }
}
