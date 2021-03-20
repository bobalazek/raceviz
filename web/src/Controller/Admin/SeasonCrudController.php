<?php

namespace App\Controller\Admin;

use App\Entity\Season;
use App\Form\Type\SeriesType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SeasonCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Season::class;
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
        $slug = TextField::new('slug');
        $series = Field::new('series')
            ->setFormType(SeriesType::class)
        ;
        $genericVehicle = AssociationField::new('genericVehicle');
        $safetyVehicle = AssociationField::new('safetyVehicle');

        return [
            $name,
            $slug,
            $series,
            $genericVehicle,
            $safetyVehicle,
        ];
    }
}
