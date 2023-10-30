<?php

namespace App\Controller\Admin;

use App\Entity\TimeSlotsTypes;
use DateTime;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ColorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

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

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            DateTimeField::new('inactive'),
            ColorField::new('color'),
        ];
    }
}
