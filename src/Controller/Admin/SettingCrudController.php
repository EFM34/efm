<?php

namespace App\Controller\Admin;

use App\Entity\Setting;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SettingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Setting::class;
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
    


    /**
     * Parametre de la page Setting 
     *
     * @param string $pageName
     * @return iterable
     */
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('website_name'),
            TextField::new('description')->hideOnIndex(),
            IntegerField::new('taxe_rate'),
            ChoiceField::new('currency')->setChoices([
                'EUR' => 'EUR',
                'USD' => 'USD',
            ]),
            TextField::new('facebookLink')->hideOnIndex(),
            TextField::new('youtubeLink')->hideOnIndex(),
            TextField::new('instagramLink')->hideOnIndex(),
            TelephoneField::new('phone')->hideOnIndex(),
            ImageField::new('logo')
                 // Ici on trouve le fichier
                ->setBasePath("assets/images/setting")
                // Et la ou on  telecharge le fichier l'image du logo 
                ->setUploadDir("/public/assets/images/setting")
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                // Le logo serra obligatoir de le mettre que lors de le creation de la page
                ->setRequired($pageName === Crud::PAGE_NEW)
            ,
            TextField::new('street'),
            TextField::new('city'),
            TextField::new('postal_code'),
            TextField::new('state'),
            
        ];
    }
    
}
