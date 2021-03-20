<?php

namespace App\Controller\Admin;

use App\Admin\Field\TimeWithMillisecondsField;
use App\Entity\RaceDriverRaceStartingGrid;
use App\Form\Type\TyresType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
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
        $timeDuration = TimeWithMillisecondsField::new('timeDuration');
        $tyres = Field::new('tyres')
            ->setFormType(TyresType::class)
        ;

        return [
            $raceDriver,
            $position,
            $timeDuration,
            $tyres,
        ];
    }
}
