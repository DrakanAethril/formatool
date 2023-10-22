<?php

namespace App\Controller\Admin;

use App\Entity\Places;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PlacesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Places::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // the labels used to refer to this entity in titles, buttons, etc.
            ->setEntityLabelInSingular('Etablissement')
            ->setEntityLabelInPlural('Etablissements')
        ;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            DateTimeField::new('inactive'),
            AssociationField::new('trainings')
                ->setFormTypeOption('by_reference', false)
                ->autocomplete()
        ];
    }
    
}
