<?php

namespace App\Controller\Admin;

use App\Entity\TopicsTrainings;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class TopicsTrainingsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TopicsTrainings::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // the labels used to refer to this entity in titles, buttons, etc.
            ->setEntityLabelInSingular('Matière par formation')
            ->setEntityLabelInPlural('Matières par formation')
        ;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('trainings')->autocomplete(),
            AssociationField::new('topics')->autocomplete(),
            IntegerField::new('cm'),
            IntegerField::new('tp'),
            IntegerField::new('td'),
            AssociationField::new('topicsGroups')->autocomplete(),
            AssociationField::new('topicsTrainingsLabels')->setFormTypeOption('by_reference', false)->autocomplete(),
            
        ];
    }
    
}
