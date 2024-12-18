<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }


    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('une categorie')
            ->setEntityLabelInPlural('Categories')
            ;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name')->setLabel('Nom')->setHelp("le nom de la catégorie"),
            SlugField::new('slug')->setLabel('Url')->setTargetFieldName('name')->setHelp("l'url de la catégorie genere automatiquement"),
        ];
    }

}
