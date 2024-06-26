<?php

namespace App\Controller\Admin;

use App\Entity\Vehicle;
use App\Form\Type\VehicleTypeType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichFileType;

class VehicleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Vehicle::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['name'])
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        $name = TextField::new('name');
        $slug = TextField::new('slug');
        $type = Field::new('type')
            ->setFormType(VehicleTypeType::class)
        ;
        $file = Field::new('file')
            ->setFormType(VichFileType::class)
            ->onlyOnForms()
        ;

        return [
            $name,
            $slug,
            $type,
            $file,
        ];
    }
}
