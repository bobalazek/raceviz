<?php

namespace App\Controller\Admin;

use App\Entity\RaceIncidentRaceDriver;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

class RaceIncidentRaceDriverCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RaceIncidentRaceDriver::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $raceIncident = AssociationField::new('raceIncident');
        $raceDriver = AssociationField::new('raceDriver');
        $description = TextareaField::new('description');

        return [
            $raceIncident,
            $raceDriver,
            $description,
        ];
    }
}
