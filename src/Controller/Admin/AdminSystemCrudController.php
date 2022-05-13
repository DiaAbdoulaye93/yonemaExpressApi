<?php

namespace App\Controller\Admin;

use App\Entity\AdminSystem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AdminSystemCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AdminSystem::class;
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
