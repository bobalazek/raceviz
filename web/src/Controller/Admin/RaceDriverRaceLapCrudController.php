<?php

namespace App\Controller\Admin;

use App\Admin\Field\TimeWithMillisecondsField;
use App\Entity\RaceDriverRaceLap;
use App\Form\Type\TyresType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
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
        $timeDuration = TimeWithMillisecondsField::new('timeDuration');
        $timeOfDay = TimeField::new('timeOfDay')
            ->setFormTypeOption('with_seconds', true)
        ;
        $tyres = Field::new('tyres')
            ->setFormType(TyresType::class)
        ;

        return [
            $raceDriver,
            $lap,
            $position,
            $timeDuration,
            $timeOfDay,
            $tyres,
        ];
    }
}
