<?php

namespace App\Controller\Admin;

use App\Entity\Topics;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TopicsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Topics::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            DateTimeField::new('inactive'),
        ];
    }
}
