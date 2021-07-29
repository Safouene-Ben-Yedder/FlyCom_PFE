<?php

namespace App\Form;

use App\Entity\Avion;
use App\Entity\CategorieAvion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class AvionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code_avion' ,null ,[
                'label' => 'Code d\'avion',
            ])
            ->add('nom_avion',null ,[
                'label' => 'Nom d\'avion',
            ])
            ->add('categorie', EntityType::class,[
                'label' => 'Catégorie d\'avion',
                'class' => CategorieAvion::class,
                'choice_label' => 'code_categorie'

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Avion::class,
        ]);
    }
}
