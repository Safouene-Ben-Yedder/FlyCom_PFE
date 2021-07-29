<?php

namespace App\Form;

use App\Entity\Tve;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Vol;
use App\Entity\Tache;
use App\Entity\Avion;
use App\Entity\Aeroport;
use App\Entity\Employe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;


class TveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('employe', EntityType::class,[
                'class' => Employe::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.etat=true AND u.type_employe=1');
                },
                'choice_label' => 'nom_employe'
            ])
            ->add('tache', EntityType::class,[
                'class' => Tache::class,
                'choice_label' => 'description'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tve::class,
        ]);
    }
}
