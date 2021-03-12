<?php

namespace App\Controller\Admin;

use App\Entity\Team;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ColorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CountryField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class TeamCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Team::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['name', 'location', 'countryCode'])
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        $name = TextField::new('name');
        $slug = TextField::new('slug');
        $location = TextField::new('location');
        $countryCode = CountryField::new('countryCode');
        $url = UrlField::new('url');
        $debutedAt = DateField::new('debutedAt');
        $defunctedAt = DateField::new('defunctedAt');
        $color = ColorField::new('color');

        return [
            $name,
            $slug,
            $location,
            $countryCode,
            $url,
            $debutedAt,
            $defunctedAt,
            $color,
        ];
    }
}
