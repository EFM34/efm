<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;



class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }


    // /**
    //  * Redirection d'une page ver uns autre
    //  *
    //  * @param Actions $actions
    //  * @return Actions
    //  */
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            // Sur la page d'édition on propose le retour ver la page d'accueil
            ->add(Crud::PAGE_EDIT, Action::INDEX)
             // Sur la page Index on propose l'affichage des détails
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            // Sur la page d'édition on propose l'affichage des détails
            ->add(Crud::PAGE_EDIT, Action::DETAIL);
    } 
    
    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            // https://symfony.com/bundles/EasyAdminBundle/current/crud.html#crud-pages
            // https://symfony.com/bundles/EasyAdminBundle/current/fields/SlugField.html#basic-information
            SlugField::new('slug')->setTargetFieldName('name'),
            TextField::new('description'),
            TextEditorField::new('more_description'),
            TextEditorField::new('additional_infos'),
            ImageField::new('imageUrls')
            // On accepte plusieur format d'image ici lister
            ->setFormTypeOptions([ 
                "multiple" => true,
                'attr' => [
                    // 'accept' => 'image/x-png, image/gif, image/jpeg, image/jpg'
                    'accept' => 'image/*'

                ]
                ])
            // On met l'url des l'images 
            ->setBasePath("assets/images/products")
            // On telecharge l'images 
            ->setUploadDir("/public/assets/images/products")
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            // Sa sera obligatoirment que on sera sur Crud la creation
            ->setRequired($pageName === Crud::PAGE_NEW)
            ,
            ->setUploadedFileNamePattern('[randomhash].[extension]'),

            MoneyField::new('solde_price')->setCurrency("EUR"),
            MoneyField::new('regular_price')->setCurrency("EUR"),
            IntegerField::new('stock'),
            AssociationField::new('categories'),
            BooleanField::new('isBestSeller'),
            BooleanField::new('isNewArrival'),
            BooleanField::new('isFeatured'),
            BooleanField::new('isSpecialOffer'),

        ];
    }
   
}
