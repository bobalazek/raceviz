<?php

namespace App\Controller\Admin;

use App\Entity\Driver;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CountryField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class DriverCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Driver::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['firstName', 'lastName', 'countryCode'])
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        $firstName = TextField::new('firstName');
        $lastName = TextField::new('lastName');
        $slug = TextField::new('slug');
        $countryCode = CountryField::new('countryCode');
        $url = UrlField::new('url');
        $ergastDriverId = TextField::new('ergastDriverId');

        return [
            $firstName,
            $lastName,
            $slug,
            $countryCode,
            $url,
            $ergastDriverId,
        ];
    }
}
