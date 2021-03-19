<?php

namespace App\Controller\Admin;

use App\Admin\Field\RaceDriverRaceResultStatusField;
use App\Admin\Field\TimeWithMillisecondsField;
use App\Entity\RaceDriverRaceResult;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

class RaceDriverRaceResultCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RaceDriverRaceResult::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $raceDriver = AssociationField::new('raceDriver');
        $position = NumberField::new('position')
            ->setFormTypeOption('html5', true)
        ;
        $points = NumberField::new('points')
            ->setFormTypeOption('html5', true)
        ;
        $timeDuration = TimeWithMillisecondsField::new('timeDuration');
        $lapsBehind = NumberField::new('lapsBehind');
        $status = RaceDriverRaceResultStatusField::new('status');
        $statusNote = TextareaField::new('statusNote');

        return [
            $raceDriver,
            $position,
            $points,
            $timeDuration,
            $lapsBehind,
            $status,
            $statusNote,
        ];
    }
}
