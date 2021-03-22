<?php

namespace App\Controller\Admin;

use App\Admin\Field\TimeWithMillisecondsField;
use App\Entity\RaceIncident;
use App\Form\Type\RaceFlagType;
use App\Form\Type\RaceIncidentTypeType;
use App\Form\Type\SafetyVehicleType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;

class RaceIncidentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RaceIncident::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['race.name'])
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        $race = AssociationField::new('race');
        $type = Field::new('type')
            ->setFormType(RaceIncidentTypeType::class)
        ;
        $flag = Field::new('flag')
            ->setFormType(RaceFlagType::class)
        ;
        $safetyVehicle = Field::new('safetyVehicle')
            ->setFormType(SafetyVehicleType::class)
        ;
        $description = TextareaField::new('description');
        $lap = NumberField::new('lap');
        $lapSector = NumberField::new('lapSector');
        $lapLocation = NumberField::new('lapLocation');
        $timeDuration = TimeWithMillisecondsField::new('timeDuration');
        $timeOfDay = TimeField::new('timeOfDay')
            ->setFormTypeOption('with_seconds', true)
        ;

        return [
            $race,
            $type,
            $flag,
            $safetyVehicle,
            $description,
            $lap,
            $lapSector,
            $lapLocation,
            $timeDuration,
            $timeOfDay,
        ];
    }
}
