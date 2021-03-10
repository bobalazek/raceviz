<?php

namespace App\Controller\Admin;

use App\Entity\RaceCarDriverRaceLapTime;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;

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
        $raceCarDriver = AssociationField::new('raceCarDriver');

        return [
            $lap,
            $position,
            $raceCarDriver,
        ];
    }
}
