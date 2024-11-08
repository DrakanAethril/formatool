<?php

namespace App\Controller\Admin;

use App\Entity\Skills;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class SkillsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Skills::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            TextField::new('short_name'),
            TextField::new('period'),
            NumberField::new('volume'),
            TextEditorField::new('description'),
            TextEditorField::new('knowledge'),
            TextEditorField::new('professional'),
            TextEditorField::new('performance'),
            IntegerField::new('cursus_order'),
            AssociationField::new('topics_group')
                //->setFormTypeOption('by_reference', false)
                ->autocomplete(),
            AssociationField::new('teacher')
                //->setFormTypeOption('by_reference', false)
                ->autocomplete(),
            DateTimeField::new('inactive'),
        ];
    }
    
}
