<?php

namespace App\Controller\Admin;

use App\Entity\Address;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CountryField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AddressCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Address::class;
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
            TextField::new('name', 'Nom de l\'adresse'),
            TextField::new('client_name'),
            ChoiceField::new('address_type')->setChoices([
                'Facturation' => 'Facturation',
                'Expédition'   => 'Expédition'
            ]),
            TextField::new('street'),
            TextField::new('postal_code'),
            TextField::new('city'),
            CountryField::new('state'),
            //         ->showFlag(false),
            AssociationField::new('user'),
            TextEditorField::new('more_details'),
        ];
    }

}
