<?php

namespace App\Controller\Admin;

use App\Admin\Field\ComplexArrayField;
use App\Entity\UserPoint;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserPointCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return UserPoint::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['key', 'amount', 'data'])
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable('new', 'edit', 'delete')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        $key = TextField::new('key');
        $amount = IntegerField::new('amount');
        $data = ComplexArrayField::new('data');
        $user = AssociationField::new('user');
        $createdAt = DateTimeField::new('createdAt');

        return [
            $key,
            $amount,
            $data,
            $user,
            $createdAt,
        ];
    }
}
