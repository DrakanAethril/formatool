<?php

namespace App\Controller\Admin;

use App\Entity\LessonTypes;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\ColorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;

class LessonTypesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return LessonTypes::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // the labels used to refer to this entity in titles, buttons, etc.
            ->setEntityLabelInSingular('Type de cours')
            ->setEntityLabelInPlural('Types de cours')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            ColorField::new('agendaColor'),
            DateTimeField::new('inactive')
        ];
    }
}
