<?php

namespace App\Controller\Admin;

use App\Admin\Field\SeriesField;
use App\Entity\Race;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RaceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Race::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['name', 'series'])
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        $name = TextField::new('name');
        $series = SeriesField::new('series');
        $laps = NumberField::new('laps');
        $url = TextField::new('url');
        $startedAt = DateTimeField::new('startedAt');
        $circuit = AssociationField::new('circuit');

        return [
            $name,
            $series,
            $laps,
            $url,
            $startedAt,
            $circuit,
        ];
    }
}
