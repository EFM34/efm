<?php 

// src/Form/ReviewType.php
namespace App\Form;

use App\Entity\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\Range;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('author', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entre votre nom',
                    'class' => 'form-group col-12 form-control'
                ],
                'row_attr' => [
                    'class' => 'form-group mb-3'
                ]
            ])
            ->add('comment', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entre votre Commentaire',
                    'class' => 'form-group col-12 form-control'
                ],
                'row_attr' => [
                    'class' => 'form-group mb-3'
                ]
                ])
            ->add('rating', IntegerType::class, [
                'label' => false,
                 'constraints' => [
                        new NotBlank([
                            'message' => 'Le commentaire ne peut pas être vide.',
                        ]),
                        new Range([
                            'min' => 1,
                            'minMessage' => 'Votre note doit avoir au maximum {{ limit }} caractères.',
                        ]) 
                ],
                'attr' => [
                    'placeholder' => 'La note doit être entre 1 et 5.',
                    'class' => 'form-group col-12 form-control'
                ],
                'row_attr' => [
                    'class' => 'form-group mb-3'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer',
                "attr" => [
                    "placeholder" => "Envoyer",
                    "class" => "btn btn-fill-out btn-block w-100",
                    "type" => "submit",
                ],
                "row_attr" => [
                    "class" => "col-md-12"
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}
