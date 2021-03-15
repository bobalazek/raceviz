<?php

namespace App\Controller\Admin;

use App\Entity\Race;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class RaceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Race::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['name', 'season.name', 'circuit.name'])
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        $name = TextField::new('name');
        $slug = TextField::new('slug');
        $laps = NumberField::new('laps');
        $url = UrlField::new('url');
        $ergastSeriesSeasonAndRound = TextField::new('ergastSeriesSeasonAndRound')
            ->setHelp('The format must be "(series)/(season)/(round)", for example "f1/2020/16"')
        ;
        $startedAt = DateTimeField::new('startedAt');
        $season = AssociationField::new('season');
        $circuit = AssociationField::new('circuit');

        return [
            $name,
            $slug,
            $laps,
            $url,
            $ergastSeriesSeasonAndRound,
            $startedAt,
            $season,
            $circuit,
        ];
    }
}
