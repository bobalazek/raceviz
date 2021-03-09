<?php

namespace App\Controller\Admin;

use App\Entity\UserBlock;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;

class UserBlockCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return UserBlock::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['user.email', 'userBlocked.email'])
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
        $user = AssociationField::new('user');
        $userBlocked = AssociationField::new('userBlocked');
        $createdAt = DateTimeField::new('createdAt');

        return [
            $user,
            $userBlocked,
            $createdAt,
        ];
    }
}
