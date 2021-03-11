<?php

namespace App\Controller\Admin;

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
            ->setSearchFields(['name', 'location', 'countryCode'])
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        $name = TextField::new('name');
        $slug = TextField::new('slug');
        $location = TextField::new('location');
        $countryCode = CountryField::new('countryCode');
        $url = TextField::new('url');
        $debutedAt = DateField::new('debutedAt');
        $defunctedAt = DateField::new('defunctedAt');

        return [
            $name,
            $slug,
            $location,
            $countryCode,
            $url,
            $debutedAt,
            $defunctedAt,
        ];
    }
}
