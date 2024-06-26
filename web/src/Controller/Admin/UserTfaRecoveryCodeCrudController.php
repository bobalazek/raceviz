<?php

namespace App\Controller\Admin;

use App\Entity\UserTfaRecoveryCode;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserTfaRecoveryCodeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return UserTfaRecoveryCode::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['recoveryCode', 'user.email'])
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
        $recoveryCode = TextField::new('recoveryCode');
        $usedAt = DateTimeField::new('usedAt');
        $user = AssociationField::new('user');
        $createdAt = DateTimeField::new('createdAt');

        return [
            $recoveryCode,
            $usedAt,
            $user,
            $createdAt,
        ];
    }
}
