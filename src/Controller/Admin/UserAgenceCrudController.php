<?php

namespace App\Controller\Admin;

use App\Entity\UserAgence;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserAgenceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return UserAgence::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
