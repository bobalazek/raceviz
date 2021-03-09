<?php

namespace App\Controller\Admin;

use App\Entity\Driver;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CountryField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class DriverCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Driver::class;
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
        $firstName = TextField::new('firstName');
        $lastName = TextField::new('lastName');
        $countryCode = CountryField::new('countryCode');
        $url = TextField::new('url');

        return [
            $id,
            $firstName,
            $lastName,
            $countryCode,
            $url,
        ];
    }
}
