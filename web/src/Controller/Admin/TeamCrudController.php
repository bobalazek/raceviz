<?php

namespace App\Controller\Admin;

use App\Admin\Field\SeriesField;
use App\Entity\Team;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CountryField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TeamCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Team::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['id', 'series', 'name', 'location', 'countryCode'])
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        $name = TextField::new('name');
        $series = SeriesField::new('series');
        $location = TextField::new('location');
        $countryCode = CountryField::new('countryCode');
        $url = TextField::new('url');
        $debutedAt = DateField::new('debutedAt');
        $defunctedAt = DateField::new('defunctedAt');

        return [
            $name,
            $series,
            $location,
            $countryCode,
            $url,
            $debutedAt,
            $defunctedAt,
        ];
    }
}
