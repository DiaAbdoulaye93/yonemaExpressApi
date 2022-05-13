<?php

namespace App\Controller\Admin;

use App\Entity\Caissier;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CaissierCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Caissier::class;
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
