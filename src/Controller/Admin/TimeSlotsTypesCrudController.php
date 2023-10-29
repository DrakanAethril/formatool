<?php

namespace App\Controller\Admin;

use App\Entity\TimeSlotsTypes;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class TimeSlotsTypesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TimeSlotsTypes::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // the labels used to refer to this entity in titles, buttons, etc.
            ->setEntityLabelInSingular('Type de périodes')
            ->setEntityLabelInPlural('Types de périodes')
        ;
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
