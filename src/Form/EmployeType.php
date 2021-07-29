<?php

namespace App\Form;

use App\Entity\Employe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\DateType;



class EmployeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cin' , null , [
                'label'  => 'CIN'
            ])
            ->add('date_de_naissance' ,DateType::class, [
                'label'  => 'Date de naissance',
                'years' => range(1970,2050),
              //  'placeholder' => 'Select a value',
            ])
            ->add('nom_employe', null , [
                'label'  => 'Nom et prénom'
            ])
            ->add('login' , null , [
                'label'  => 'Nom d\'utlisateur'
            ])
           /* ->add('mot_de_passe', PasswordType::class,[
                'required' => true,
            ])
            ->add('confirm_mdp', PasswordType::class,[
                'required' => true,
            ])*/
            ->add('email')
            ->add('photo', FileType::class, [
                'label' => 'Photo de profile',
                
                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/gif',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Vous devez choisir une image',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Employe::class,
        ]);
    }
}
