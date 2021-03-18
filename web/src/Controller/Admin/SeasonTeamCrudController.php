<?php

namespace App\Controller\Admin;

use App\Entity\SeasonTeam;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class SeasonTeamCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SeasonTeam::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['number', 'season.name', 'team.name'])
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        $season = AssociationField::new('season');
        $team = AssociationField::new('team');
        $vehicle = AssociationField::new('vehicle');

        return [
            $season,
            $team,
            $vehicle,
        ];
    }
}
