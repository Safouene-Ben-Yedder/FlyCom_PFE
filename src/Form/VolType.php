<?php

namespace App\Form;

use App\Entity\Vol;
use App\Entity\Tache;
use App\Entity\Avion;
use App\Entity\Aeroport;
use App\Entity\Tve;
use App\Entity\Employe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;



class VolType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('num_vol' , null , [
                'label'  => 'Numéro de vol',
            ])            
            ->add('date_depart' , DateTimeType::class, [
                'label' => 'Date de départ',
                'years' => range(1970,2050),
              //  'placeholder' => 'Select a value',
            ])
            ->add('date_arrivee', DateTimeType::class, [
                'label' => 'Date d\'arrivée',
                'years' => range(1970,2050),
            ])
            ->add('aeroport_depart', EntityType::class,[
                'label' => 'Aéroport de départ',
                'class' => Aeroport::class,
                'choice_label' => 'nom_aeroport'

            ])
            ->add('aeroport_arrivee', EntityType::class,[
                'label' => 'Aéroport d\arrivée',
                'class' => Aeroport::class,
                'choice_label' => 'nom_aeroport'
            ])
            ->add('aeroports_escales', EntityType::class,[
                'label' => 'Aéroports d\'escales',
                'class' => Aeroport::class,
                'choice_label' => 'nom_aeroport',
                'multiple' => true,
                'required' => false,
            ])
            ->add('avion', EntityType::class,[
                'label' => 'Avion',
                'class' => Avion::class,
                'choice_label' => 'code_avion'

            ])    
            ->add('tves', CollectionType::class, [
                'label' => 'Planification des tâches',
                'entry_type' => TveType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ])
            ->add('taches_commencees', HiddenType::class, [
                'empty_data' => 0,                
            ])
            ->add('taches_terminees', HiddenType::class, [
                'empty_data' => 0,                
            ])       
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Vol::class,
        ]);
    }
}
