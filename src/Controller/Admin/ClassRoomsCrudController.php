<?php

namespace App\Controller\Admin;

use App\Entity\ClassRooms;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class ClassRoomsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ClassRooms::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // the labels used to refer to this entity in titles, buttons, etc.
            ->setEntityLabelInSingular('Salle')
            ->setEntityLabelInPlural('Salles')
        ;
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            AssociationField::new('place')
                ->autocomplete(),
            AssociationField::new('trainings')
                ->autocomplete(),
            DateTimeField::new('inactive'),
            
        ];
    }
    
}
