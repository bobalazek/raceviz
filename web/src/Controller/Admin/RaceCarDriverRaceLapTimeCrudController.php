<?php

namespace App\Controller\Admin;

use App\Admin\Field\TimeWithMillisecondsField;
use App\Entity\RaceCarDriverRaceLapTime;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;

class RaceCarDriverRaceLapTimeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RaceCarDriverRaceLapTime::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud;
    }

    public function configureFields(string $pageName): iterable
    {
        $lap = NumberField::new('lap');
        $position = NumberField::new('position');
        $time = TimeWithMillisecondsField::new('time');
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
