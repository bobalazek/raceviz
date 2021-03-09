<?php

namespace App\Controller\Admin;

use App\Entity\Constructor;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CountryField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ConstructorCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Constructor::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['id', 'firstName', 'lastName', 'countryCode'])
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        $id = IdField::new('id');
        $name = TextField::new('name');
        $location = TextField::new('location');
        $countryCode = CountryField::new('countryCode');
        $url = TextField::new('url');
        $debutedAt = DateField::new('debutedAt');
        $defunctedAt = DateField::new('defunctedAt');

        return [
            $id,
            $name,
            $location,
            $countryCode,
            $url,
            $debutedAt,
            $defunctedAt,
        ];
    }
}
