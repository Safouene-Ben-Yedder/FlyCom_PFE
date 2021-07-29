<?php

namespace App\Form;

use App\Entity\Employe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;



class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cin')
            ->add('date_de_naissance' , null, [
               
                'years' => range(1970,2050),
            ])
            ->add('nom_employe')
            ->add('type_employe', HiddenType::class, [
                'empty_data' => 1,
            ])
            ->add('login')
            ->add('mot_de_passe', PasswordType::class)
            ->add('confirm_mdp', PasswordType::class)
            ->add('email', EmailType::class)
            ->add('etat', HiddenType::class, [
                'empty_data' => false,                
            ])
            ->add('active', HiddenType::class, [
                'empty_data' => true,                
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
