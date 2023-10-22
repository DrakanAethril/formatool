<?php

namespace App\Controller\Admin;

use App\Entity\Trainings;
use App\Entity\Places;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TrainingsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Trainings::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // the labels used to refer to this entity in titles, buttons, etc.
            ->setEntityLabelInSingular('Formation')
            ->setEntityLabelInPlural('Formations')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
            TextField::new('level'),
            DateTimeField::new('inactive'),
            AssociationField::new('place')
                ->autocomplete(),
            AssociationField::new('owner')
                ->autocomplete()
        ];
    }
    
}
