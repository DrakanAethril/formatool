<?php

namespace App\Controller\Admin;

use App\Entity\Topics;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class TopicsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Topics::class;
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
