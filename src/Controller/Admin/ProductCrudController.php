<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }


    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('un produit')
            ->setEntityLabelInPlural('Produits')
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        $required = true ;

        if ($pageName == 'edit'){
            $required = false ;
        }

        return [
            TextField::new('name'),
            SlugField::new('slug')->setTargetFieldName('name')->setLabel('url du produit généré automatiquement'),
            TextEditorField::new('description'),
            ImageField::new('illustration')
                ->setLabel('image de votre produit')
                ->setHelp('image du produit en 600x600')
                ->setUploadedFileNamePattern('[year]-[month]-[day]-[contenthash].[extension]')
                ->setBasePath('/uploads')
                ->setUploadDir('/public/uploads')
                ->setRequired($required),
            NumberField::new('price')->setLabel('prix hors taxe'),
            ChoiceField::new('tva')->setLabel('tva')->setChoices([
                '5.5%'=> '5.5',
                '10%'=> '10',
                '20%'=> '20',
            ]),
            AssociationField::new('category')->setLabel('categorie du produit'),

        ];
    }
}
