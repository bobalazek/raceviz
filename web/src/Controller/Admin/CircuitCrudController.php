<?php

namespace App\Controller\Admin;

use App\Entity\Circuit;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CountryField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CircuitCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Circuit::class;
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

        return [
            $name,
            $slug,
            $location,
            $countryCode,
            $url,
        ];
    }
}
