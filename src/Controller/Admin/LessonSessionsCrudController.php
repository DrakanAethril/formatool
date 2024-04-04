<?php

namespace App\Controller\Admin;

use App\Entity\LessonSessions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;

class LessonSessionsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return LessonSessions::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // the labels used to refer to this entity in titles, buttons, etc.
            ->setEntityLabelInSingular('Cours')
            ->setEntityLabelInPlural('Cours')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
            DateField::new('day'),
            TimeField::new('startHour'),
            TimeField::new('endHour'),
            IntegerField::new('length'),
            AssociationField::new('training')
                ->autocomplete(),
            AssociationField::new('classRooms')
                ->autocomplete(),
            AssociationField::new('topic')
        ];
    }
    
}
