<?php

namespace App\Controller\Admin;

use App\Admin\Field\TimeWithMillisecondsField;
use App\Entity\RaceCarDriverRacePitStop;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;

class RaceCarDriverRacePitStopCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RaceCarDriverRacePitStop::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $raceCarDriver = AssociationField::new('raceCarDriver');
        $lap = NumberField::new('lap')
            ->setFormTypeOption('html5', true)
        ;
        $time = TimeWithMillisecondsField::new('time');
        $timeOfDay = TimeField::new('timeOfDay')
            ->setFormTypeOption('with_seconds', true)
        ;

        return [
            $raceCarDriver,
            $lap,
            $time,
            $timeOfDay,
        ];
    }
}
