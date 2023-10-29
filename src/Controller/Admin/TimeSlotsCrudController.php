<?php

namespace App\Controller\Admin;

use App\Entity\TimeSlots;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TimeSlotsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TimeSlots::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // the labels used to refer to this entity in titles, buttons, etc.
            ->setEntityLabelInSingular('Période')
            ->setEntityLabelInPlural('Périodes')
        ;
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            DateTimeField::new('start_date'),
            DateTimeField::new('end_date'),
            DateTimeField::new('inactive'),
            AssociationField::new('training')
                ->autocomplete(),
            AssociationField::new('timeSlotsTypes')
                ->autocomplete()
            
        ];
    }
    
}
