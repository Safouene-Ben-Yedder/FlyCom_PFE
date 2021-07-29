<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Vol;
use App\Entity\Tahce;
use App\Entity\Avion;
use App\Entity\Aeroport;
use App\Entity\Tve;
use App\Entity\Employe;
use App\Entity\Intervention;
use App\Repository\EmployeRepository;
use App\Repository\TveRepository;
use App\Repository\VolRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class StatistiquesController extends AbstractController
{
    /**
     * @Route("/statistiques", name="statistiques")
     */
    public function afficher_statistiques(Request $request,ObjectManager $entityManager)
    {
    
      //  if ($request->isXmlHttpRequest() ) {  

            $jsonData = array();  
            $idx = 0;  

            $annee7=date('Y');
            $annee6=$annee7-1;
            $annee5=$annee6-1;
            $annee4=$annee5-1;
            $annee3=$annee4-1;
            $annee2=$annee3-1;
            $annee1=$annee2-1;

            $a=count($this->getDoctrine() 
            ->getRepository(Vol::class) 
            ->createQueryBuilder('a')
               ->where('a.date_arrivee LIKE :date_arrivee AND a.retard IS NOT NULL')
               ->setParameter('date_arrivee', '%'.$annee7.'%')
               ->getQuery()
               ->getResult());

            $b=count($this->getDoctrine() 
            ->getRepository(Vol::class) 
            ->createQueryBuilder('a')
               ->where('a.date_arrivee LIKE :date_arrivee AND a.retard=1')
               ->setParameter('date_arrivee', '%'.$annee7.'%')
               ->getQuery()
               ->getResult());

            if($a != 0){   
                $x=(100*$b)/$a;
            }
            else{
                $x=0;
            }

            $pannee7=number_format($x, 2);


            $a=count($this->getDoctrine() 
            ->getRepository(Vol::class) 
            ->createQueryBuilder('a')
               ->where('a.date_arrivee LIKE :date_arrivee AND a.retard IS NOT NULL ')
               ->setParameter('date_arrivee', '%'.$annee6.'%')
               ->getQuery()
               ->getResult());

            $b=count($this->getDoctrine() 
            ->getRepository(Vol::class) 
            ->createQueryBuilder('a')
               ->where('a.date_arrivee LIKE :date_arrivee AND a.retard=1')
               ->setParameter('date_arrivee', '%'.$annee6.'%')
               ->getQuery()
               ->getResult());
           
            if($a != 0){   
                $x=(100*$b)/$a;
            }
            else{
                $x=0;
            }

            $pannee6=number_format($x, 2);


            $a=count($this->getDoctrine() 
            ->getRepository(Vol::class) 
            ->createQueryBuilder('a')
               ->where('a.date_arrivee LIKE :date_arrivee AND a.retard IS NOT NULL')
               ->setParameter('date_arrivee', '%'.$annee5.'%')
               ->getQuery()
               ->getResult());

            $b=count($this->getDoctrine() 
            ->getRepository(Vol::class) 
            ->createQueryBuilder('a')
               ->where('a.date_arrivee LIKE :date_arrivee AND a.retard=1')
               ->setParameter('date_arrivee', '%'.$annee5.'%')
               ->getQuery()
               ->getResult());
           
            if($a != 0){   
                $x=(100*$b)/$a;
            }
            else{
                $x=0;
            }

            $pannee5=number_format($x, 2);



            $a=count($this->getDoctrine() 
            ->getRepository(Vol::class) 
            ->createQueryBuilder('a')
               ->where('a.date_arrivee LIKE :date_arrivee AND a.retard IS NOT NULL')
               ->setParameter('date_arrivee', '%'.$annee4.'%')
               ->getQuery()
               ->getResult());

            $b=count($this->getDoctrine() 
            ->getRepository(Vol::class) 
            ->createQueryBuilder('a')
               ->where('a.date_arrivee LIKE :date_arrivee AND a.retard=1')
               ->setParameter('date_arrivee', '%'.$annee4.'%')
               ->getQuery()
               ->getResult());
           
            if($a != 0){   
                $x=(100*$b)/$a;
            }
            else{
                $x=0;
            }

            $pannee4=number_format($x, 2);


            $a=count($this->getDoctrine() 
            ->getRepository(Vol::class) 
            ->createQueryBuilder('a')
               ->where('a.date_arrivee LIKE :date_arrivee AND a.retard IS NOT NULL')
               ->setParameter('date_arrivee', '%'.$annee3.'%')
               ->getQuery()
               ->getResult());

            $b=count($this->getDoctrine() 
            ->getRepository(Vol::class) 
            ->createQueryBuilder('a')
               ->where('a.date_arrivee LIKE :date_arrivee AND a.retard=1')
               ->setParameter('date_arrivee', '%'.$annee3.'%')
               ->getQuery()
               ->getResult());
           
            if($a != 0){   
                $x=(100*$b)/$a;
            }
            else{
                $x=0;
            }

            $pannee3=number_format($x, 2);


            $a=count($this->getDoctrine() 
            ->getRepository(Vol::class) 
            ->createQueryBuilder('a')
               ->where('a.date_arrivee LIKE :date_arrivee AND a.retard IS NOT NULL')
               ->setParameter('date_arrivee', '%'.$annee2.'%')
               ->getQuery()
               ->getResult());

            $b=count($this->getDoctrine() 
            ->getRepository(Vol::class) 
            ->createQueryBuilder('a')
               ->where('a.date_arrivee LIKE :date_arrivee AND a.retard=1')
               ->setParameter('date_arrivee', '%'.$annee2.'%')
               ->getQuery()
               ->getResult());
           
            if($a != 0){   
                $x=(100*$b)/$a;
            }
            else{
                $x=0;
            }

            $pannee2=number_format($x, 2);


            $a=count($this->getDoctrine() 
            ->getRepository(Vol::class) 
            ->createQueryBuilder('a')
               ->where('a.date_arrivee LIKE :date_arrivee AND a.retard IS NOT NULL')
               ->setParameter('date_arrivee', '%'.$annee1.'%')
               ->getQuery()
               ->getResult());

            $b=count($this->getDoctrine() 
            ->getRepository(Vol::class) 
            ->createQueryBuilder('a')
               ->where('a.date_arrivee LIKE :date_arrivee AND a.retard=1')
               ->setParameter('date_arrivee', '%'.$annee1.'%')
               ->getQuery()
               ->getResult());
           
            if($a != 0){   
                $x=(100*$b)/$a;
            }
            else{
                $x=0;
            }

            $pannee1=number_format($x, 2);


            $nbr_totale_employes= count($this->getDoctrine() 
            ->getRepository(Employe::class) 
            ->createQueryBuilder('a')
               ->where('a.etat=1 AND a.type_employe != 2')
               ->getQuery()
               ->getResult());

            $nbr_totale_vols= count($this->getDoctrine() 
            ->getRepository(Vol::class) 
            ->createQueryBuilder('a')
               ->where('a.retard IS NOT NULL')
               ->getQuery()
               ->getResult());



          
          /*  $a = count($entityManager->createQuery(
               'SELECT t, v
                FROM App\Entity\Tve t
                INNER JOIN t.vol v
                WHERE v.retard IS NULL')
                ->getResult());
            
            $b= count($entityManager->createQuery(
               'SELECT t, v
                FROM App\Entity\Tve t
                INNER JOIN t.vol v
                WHERE v.retard IS NULL AND t.date_fin_tache IS NOT NULL')
                ->getResult());  */
               

            $a=count($this->getDoctrine() 
            ->getRepository(Tve::class) 
            ->createQueryBuilder('a')
            ->leftJoin('a.vol', 'v')
               ->where('v.retard IS NULL')
               ->getQuery()
               ->getResult());   

            $b=count($this->getDoctrine() 
            ->getRepository(Tve::class) 
            ->createQueryBuilder('a')
            ->leftJoin('a.vol', 'v')
               ->where('v.retard IS NULL AND a.date_fin_tache IS NOT NULL')
               ->getQuery()
               ->getResult()); 
            
            if($a != 0){   
                $x=(100*$b)/$a;
            }
            else{
                $x=0;
            }

            $progression_preparatifs=number_format($x, 0); 

            $nbr_vol_auj=count($this->getDoctrine() 
            ->getRepository(Vol::class) 
            ->createQueryBuilder('a')
               ->where('a.date_depart LIKE :date_depart')
               ->setParameter('date_depart', date("Y-m-d").'%')
               ->getQuery()
               ->getResult());

            $nbr_vol_en_cours=count($this->getDoctrine() 
            ->getRepository(Vol::class) 
            ->createQueryBuilder('a')
               ->where('a.taches_commencees > 0 AND a.retard IS NULL')
               ->getQuery()
               ->getResult());

            $nbr_vol_a_preparer=count($this->getDoctrine() 
            ->getRepository(Vol::class) 
            ->createQueryBuilder('a')
               ->where('a.taches_commencees = 0')
               ->getQuery()
               ->getResult());

            $nbr_vol_en_retrad=count($this->getDoctrine() 
            ->getRepository(Vol::class) 
            ->createQueryBuilder('a')
               ->where('a.taches_commencees > 0 AND a.retard IS NULL AND a.retard_prediction = 1')
               ->getQuery()
               ->getResult());


            $temp = array(
            'annee1'  => $annee1 ,
            'annee2'  => $annee2,
            'annee3'  => $annee3,
            'annee4'  => $annee4,
            'annee5'  => $annee5,
            'annee6'  => $annee6,
            'annee7'  => $annee7,
            'pannee1'  => $pannee1 .'%',
            'pannee2'  => $pannee2 .'%',
            'pannee3'  => $pannee3 .'%',
            'pannee4'  => $pannee4 .'%',
            'pannee5'  => $pannee5 .'%',
            'pannee6'  => $pannee6 .'%',
            'pannee7'  => $pannee7 .'%',
            'nbr_totale_employes'  => $nbr_totale_employes,
            'nbr_totale_vols'  => $nbr_totale_vols,
            'progression_preparatifs'  => $progression_preparatifs,
            'nbr_vol_auj'  => $nbr_vol_auj,
            'nbr_vol_en_cours'  => $nbr_vol_en_cours,
            'nbr_vol_a_preparer'  => $nbr_vol_a_preparer,
            'nbr_vol_en_retrad'  => $nbr_vol_en_retrad,
           
            
                );                    
            

         //   $jsonData[$idx++] = $temp;  
                         
                

            
          //  return new JsonResponse($jsonData); 
       //  } else { 
        return $this->render('statistiques/statistiques1.html.twig', [
         'annee1'  => $annee1 ,
         'annee2'  => $annee2,
         'annee3'  => $annee3,
         'annee4'  => $annee4,
         'annee5'  => $annee5,
         'annee6'  => $annee6,
         'annee7'  => $annee7,
         'pannee1'  => $pannee1 .'%',
         'pannee2'  => $pannee2 .'%',
         'pannee3'  => $pannee3 .'%',
         'pannee4'  => $pannee4 .'%',
         'pannee5'  => $pannee5 .'%',
         'pannee6'  => $pannee6 .'%',
         'pannee7'  => $pannee7 .'%',
         'nbr_totale_employes'  => $nbr_totale_employes,
         'nbr_totale_vols'  => $nbr_totale_vols,
         'progression_preparatifs'  => $progression_preparatifs,
         'nbr_vol_auj'  => $nbr_vol_auj,
         'nbr_vol_en_cours'  => $nbr_vol_en_cours,
         'nbr_vol_a_preparer'  => $nbr_vol_a_preparer,
         'nbr_vol_en_retrad'  => $nbr_vol_en_retrad, 
         ]);
       // }
    }
    
}
