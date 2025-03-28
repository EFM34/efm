<?php

namespace App\Controller\Admin;

use App\Entity\Collection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CollectionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Collection::class;
    }

      /**
     * Redirection d'une page ver uns autre
     *
     * @param Actions $actions
     * @return Actions
     */
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
            TextField::new('title'),
            TextField::new('description'),
            TextField::new('button_text'),
            TextField::new('button_link'),
            ImageField::new('imageUrl')
                // On met l'url des l'images 
                ->setBasePath("assets/images/collections")
                // On telecharge l'images 
                ->setUploadDir("/public/assets/images/collections")
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                // L'image sera obligatoirment que on sera sur la creation
                ->setRequired($pageName === Crud::PAGE_NEW),
        ];
    }
    
}
