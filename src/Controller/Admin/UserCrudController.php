<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Symfony\Component\Form\FormEvents;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Symfony\Component\Form\FormBuilderInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class UserCrudController extends AbstractCrudController
{


    /**
     * Utilisation de l'ingection du Constructeur $userPasswordHasher
     *
     * @param UserPasswordHasherInterface $userPasswordHasher
     */
    public function __construct(
        public UserPasswordHasherInterface $userPasswordHasher
    ){ 
        $this->userPasswordHasher = $userPasswordHasher;
    }


    /**
     * Return la Class User
     *
     * @return string
     */
    public static function getEntityFqcn(): string
    {
        return User::class;
    }


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
     * Return le nom de la page sur la que on est ici User.
     *
     * @param string $pageName
     * @return iterable
     */
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            ChoiceField::new('civility')->setChoices([
                'Monsieur' => 'Mr',
                'Madame'   => 'Mme',
                'Mademoiselle' => 'Mlle',
            ]),
            TextField::new('full_name'),
            EmailField::new('email'),
            TextField::new('password')
                ->setFormType(RepeatedType::class)
                ->setFormTypeOptions([
                    'type' => PasswordType::class,
                    'first_options' => [
                    'label' => 'Password',
                    'row_attr' => [
                        'class' => "col-md-6 col-xxl-5"
                    ]
                ],
                    'second_options' => [
                        'label' => 'Confirm Password',
                        'row_attr' => [
                            'class' => "col-md-6 col-xxl-5"
                        ]
                    ],
                    'mapped' => false,
                ])
                // Si on et sur la page de création des données le mdp est obligatoir
                ->setRequired($pageName === Crud::PAGE_NEW)
            ->onlyOnForms(),
        ];
    }
    

    /**
     * On detecte la création qui est initier on fait sistematiquement apell a addPasswordEventListener
     *
     * @param EntityDto $entityDto
     * @param KeyValueStore $formOptions
     * @param AdminContext $context
     * @return FormBuilderInterface
     */
    public function createNewFormBuilder(
        EntityDto $entityDto, 
        KeyValueStore $formOptions, 
        AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createNewFormBuilder($entityDto, $formOptions, $context);

        // On intercepte les données du formulaire avec EventListener
        return $this->addPasswordEventListener($formBuilder);
    }   


    /**
     * On detecte si l'edition est initier on fait sistematiquement apell a addPasswordEventListener
     *
     * @param EntityDto $entityDto
     * @param KeyValueStore $formOptions
     * @param AdminContext $context
     * @return FormBuilderInterface
     */
    public function createEditeFormBuilder(
        EntityDto $entityDto, 
        KeyValueStore $formOptions, 
        AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createEditFormBuilder($entityDto, $formOptions, $context);

        // On intercepte les données du formulaire avec EventListener
        return $this->addPasswordEventListener($formBuilder);
       
    }


    /**
     * On ajoute un evenement sur formBuilder quand l'evenment sera declancher quand les données seront sumise
     * Sistematiquement l'action se déclanche pour l'encodage de mdp ici hashPassword
     * 
     * @param FormBuilderInterface $formBuilderInterface
     * @return Traitement de l'encodage du mot de pase
     */
    public function addPasswordEventListener(FormBuilderInterface $formBuilder) {
        // On pose un evenement sur formBuilder
        return $formBuilder->addEventListener(FormEvents::POST_SUBMIT, $this->hashPassword());
    }  

  
    /**
     * Traitement de l'encodage du mot de pase
     * On encode mdp du user Get a user from the Security Token Storage.
     * @return 
     */
    public function hashPassword() {
        return function($event) {
            $form =  $event->getForm();
            // On regarde si le form est valide
            if(!$form->isValid()) {
                // On arrete l'action
                return;
            }
            // On récupere le mdp et on récupere les données
            $password = $form->get('password')->getData();

            // On rgarde si les champ n'est pas vide
            if($password === null){
                return;
            }

            // On encode le mdp on lui passe l'user et les données reçu
            $hash = $this->userPasswordHasher->hashPassword($this->getUser(), $password);
            $form->getData()->setPassword($hash);

        };
    }

}

