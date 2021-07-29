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


use App\Form\VolType;

use Spipu\Html2Pdf\Html2Pdf;



class VolController extends AbstractController
{
   

    /**
     * @Route("/vol/vols_en_cours", name="admin_affiche_vols_en_cours")
     */
    public function affiche_vols_en_cours(Request $request)
    {
    
        $vols = $this->getDoctrine() 
        ->getRepository(Vol::class) 
        ->findAll();

        if ($request->isXmlHttpRequest() ) {  
            $jsonData = array();  
            $idx = 0;  
            foreach($vols as $vol) { 

                $tves=$vol->getTves();
                $verif=true;
                foreach($tves as $tve){
                    if( !is_null($tve->getDateDebutTache()) ){
                        $verif=false;
                    }
                }


                if(!$verif){
             
                    if( is_null($vol->getRetard()) ){
                        $temp = array(
                        'id_vol'  => $vol->getId(),
                        'retard_prediction'  => $vol->getRetardPrediction(),
                        'num_vol'  => $vol->getNumVol(),
                        'aeroport_arrivee' => ($vol->getAeroportArrivee())->getNomAeroport(),  
                        'aeroport_depart' => ($vol->getAeroportDepart())->getNomAeroport(),
                        'aeroports_escales' => ($vol->getAeroportsEscales())->count(),                    
                        'date_arrivee' => ($vol->getDateArrivee())->format('Y-m-d H:i:s'),  
                        'date_depart' => ($vol->getDateDepart())->format('Y-m-d H:i:s'),  
                        'tves' => $vol->getTves(),  
                        'code_avion' => ($vol->getAvion())->getCodeAvion(),  
                            );                    
                        

                                $jsonData[$idx++] = $temp;  
                        } 
                }

            }
            return new JsonResponse($jsonData); 
         } else { 
            return $this->render('vol/afficheVolsEnCours.html.twig'); 
         } 
 
        




       // return $this->render('employe/show.html.twig', [
        //    'employe' => $employe , 
       // ]);
    }




    /**
     * @Route("/vol/vols_a_preparer", name="admin_affiche_vols_a_preparer")
     */
    public function affiche_vols_a_preparer(Request $request)
    {
    
        $vols = $this->getDoctrine() 
        ->getRepository(Vol::class) 
        ->findAll();

        if ($request->isXmlHttpRequest() ) {  
            $jsonData = array();  
            $idx = 0;  
            foreach($vols as $vol) { 

                $tves=$vol->getTves();
                $verif=true;
                foreach($tves as $tve){
                    if( !is_null($tve->getDateDebutTache()) ){
                        $verif=false;
                    }
                }


                if($verif){
             
                    if( is_null($vol->getRetard()) ){
                        $temp = array(
                        'id_vol'  => $vol->getId(),
                        'num_vol'  => $vol->getNumVol(),
                        'aeroport_arrivee' => ($vol->getAeroportArrivee())->getNomAeroport(),  
                        'aeroport_depart' => ($vol->getAeroportDepart())->getNomAeroport(),
                        'aeroports_escales' => ($vol->getAeroportsEscales())->count(),                    
                        'date_arrivee' => ($vol->getDateArrivee())->format('Y-m-d H:i:s'),  
                        'date_depart' => ($vol->getDateDepart())->format('Y-m-d H:i:s'),  
                        'tves' => $vol->getTves(),  
                        'code_avion' => ($vol->getAvion())->getCodeAvion(),  
                            );                    
                        

                                $jsonData[$idx++] = $temp;  
                        } 
                }

            }
            return new JsonResponse($jsonData); 
         } else { 
            return $this->render('vol/showVolAPreparer.html.twig'); 
         } 
 
        




       // return $this->render('employe/showVolAPreparer.html.twig', [
        //    'employe' => $employe , 
       // ]);
    }









     /**
     * @Route("/vol/vols_passes", name="admin_affiche_vols_passes")
     */
    public function affiche_vols_passes(Request $request)
    {
    
        $vols = $this->getDoctrine() 
        ->getRepository(Vol::class) 
        ->findAll();

        if ($request->isXmlHttpRequest() ) {  
            $jsonData = array();  
            $idx = 0;  
            foreach($vols as $vol) { 
             
              if( !is_null($vol->getRetard()) ){
                  $temp = array(
                  'id_vol'  => $vol->getId(),
                  'num_vol'  => $vol->getNumVol(),
                  'aeroport_arrivee' => ($vol->getAeroportArrivee())->getNomAeroport(),  
                  'aeroport_depart' => ($vol->getAeroportDepart())->getNomAeroport(),
                  'aeroports_escales' => ($vol->getAeroportsEscales())->count(),                    
                  'date_arrivee' => ($vol->getDateArrivee())->format('Y-m-d H:i:s'),  
                  'date_depart' => ($vol->getDateDepart())->format('Y-m-d H:i:s'),  
                  'tves' => $vol->getTves(),  
                  'code_avion' => ($vol->getAvion())->getCodeAvion(),  
                  'retard' => $vol->getRetard(), 
                  'intervention' => $vol->getIntervention(), 
                    );    
                    
                    if( !is_null($vol->getIntervention()) ){
                        $temp['intervention'] = ($vol->getIntervention())->getDescription();
                    } 
                    
                    
                  

                        $jsonData[$idx++] = $temp;  
                } 
            }
            return new JsonResponse($jsonData); 
         } else { 
            return $this->render('vol/afficheVolsPasses.html.twig'); 
         } 
        


       // return $this->render('employe/show.html.twig', [
        //    'employe' => $employe , 
       // ]);
    }






    /**
     * @Route("/vol/aeroports_escales/{id}", name="admin_affiche_aeroports_escales")
     */
    public function affiche_aeroports_escales($id ,Request $request)
    {
    
        $vol = $this->getDoctrine() 
        ->getRepository(Vol::class) 
        ->find($id);

        if ($request->isXmlHttpRequest() ) {  
            $jsonData = array();  
            $idx = 0;  
            foreach($vol->getAeroportsEscales() as $aeroport) { 
            
                  $temp = array(
                  'nom_aeroport'  => $aeroport->getNomAeroport(),
                  );                    

                        $jsonData[$idx++] = $temp;  
                
            }
            return new JsonResponse($jsonData); 
         } else { 
            return $this->render('vol/afficheVolsEnCours.html.twig'); 
         } 
        
    
    }


   /**
     * @Route("/vol/reclamations_non_traites_vols_en_cours", name="admin_affiche_reclamations_non_traites_vols_en_cours")
     */
    public function affiche_reclamations_non_traites_vols_en_cours(Request $request)
    {
    
        $vols = $this->getDoctrine() 
        ->getRepository(Vol::class) 
        ->findAll();

        if ($request->isXmlHttpRequest() ) {  
            $jsonData = array();  
            $idx = 0;  
            foreach($vols as $vol) { 

                $tves=$vol->getTves();
                $verif=true;
                foreach($tves as $tve){
                    if( !is_null($tve->getDateDebutTache()) ){
                        $verif=false;
                    }
                }


                if(!$verif){
             
                    if( is_null($vol->getRetard()) ){

                        foreach($vol->getTves() as $tve){
                            if( !is_null($tve->getReclamation()) and !(($tve->getReclamation())->getTraite()) ){
                            
                                    $temp = array(
                                        'num_vol'  => ($tve->getVol())->getNumVol(),
                                        'tache' => ($tve->getTache())->getDescription(),  
                                        'date_debut_tache' => $tve->getDateDebutTache(),                    
                                        'cin' => ($tve->getEmploye())->getCin(),
                                        'nom_employe' => ($tve->getEmploye())->getNomEmploye(),
                                        'reclamation' => ($tve->getReclamation())->getDescription(),
                                        'id_rec'=> ($tve->getReclamation())->getId(),
                                        'date_rec'=> (($tve->getReclamation())->getDateReclamation())->format('Y-m-d H:i:s')                     
                                            );                    
                                        
        
                                        if( !is_null($tve->getDateDebutTache()) ){
                                                $temp['date_debut_tache'] = ($tve->getDateDebutTache())->format('Y-m-d H:i:s');
                                         }

                            
                                            
                                    
                               $jsonData[$idx++] = $temp; 
                                               
                            }           
                               
                                 
                        }        


                    } 
                }

            }
            return new JsonResponse($jsonData); 
         } else { 
            return $this->render('vol/afficheVolsEnCours.html.twig'); 
         } 
 
        


        
    
    }






     /**
     * @Route("/vol/reclamations_traites_vols_en_cours", name="admin_affiche_reclamations_traites_vols_en_cours")
     */
    public function affiche_reclamations_traites_vols_en_cours(Request $request)
    {
    
        $vols = $this->getDoctrine() 
        ->getRepository(Vol::class) 
        ->findAll();

        if ($request->isXmlHttpRequest() ) {  
            $jsonData = array();  
            $idx = 0;  
            foreach($vols as $vol) { 

                $tves=$vol->getTves();
                $verif=true;
                foreach($tves as $tve){
                    if( !is_null($tve->getDateDebutTache()) ){
                        $verif=false;
                    }
                }


                if(!$verif){
             
                    if( is_null($vol->getRetard()) ){

                        foreach($vol->getTves() as $tve){
                            if( !is_null($tve->getReclamation()) and (($tve->getReclamation())->getTraite()) ){
                            
                                    $temp = array(
                                        'num_vol'  => ($tve->getVol())->getNumVol(),
                                        'tache' => ($tve->getTache())->getDescription(),                   
                                        'cin' => ($tve->getEmploye())->getCin(),
                                        'nom_employe' => ($tve->getEmploye())->getNomEmploye(),
                                        'reclamation' => ($tve->getReclamation())->getDescription(),
                                        'id_rec'=> ($tve->getReclamation())->getId(),
                                        'date_rec'=> (($tve->getReclamation())->getDateReclamation())->format('Y-m-d H:i:s')                     
                                            );                    
                                        
        
                                                                           
                                    
                               $jsonData[$idx++] = $temp; 
                                               
                            }           
                               
                                 
                        }        


                    } 
                }

            }
            return new JsonResponse($jsonData); 
         } else { 
            return $this->render('vol/afficheVolsEnCours.html.twig'); 
         } 
 
        

    
    }



    /**
     * @Route("/vol/reclamations_non_traites_vols_a_preparer", name="admin_affiche_reclamations_non_traites_vols_a_preparer")
     */
    public function affiche_reclamations_non_traites_vols_a_preparer(Request $request)
    {
    
        $vols = $this->getDoctrine() 
        ->getRepository(Vol::class) 
        ->findAll();

        if ($request->isXmlHttpRequest() ) {  
            $jsonData = array();  
            $idx = 0;  
            foreach($vols as $vol) { 

                $tves=$vol->getTves();
                $verif=true;
                foreach($tves as $tve){
                    if( !is_null($tve->getDateDebutTache()) ){
                        $verif=false;
                    }
                }


                if($verif){
             
                    if( is_null($vol->getRetard()) ){

                        foreach($vol->getTves() as $tve){
                            if( !is_null($tve->getReclamation()) and !(($tve->getReclamation())->getTraite()) ){
                            
                                    $temp = array(
                                        'num_vol'  => ($tve->getVol())->getNumVol(),
                                        'tache' => ($tve->getTache())->getDescription(),             
                                        'cin' => ($tve->getEmploye())->getCin(),
                                        'nom_employe' => ($tve->getEmploye())->getNomEmploye(),
                                        'reclamation' => ($tve->getReclamation())->getDescription(),
                                        'id_rec'=> ($tve->getReclamation())->getId(),
                                        'date_rec'=> (($tve->getReclamation())->getDateReclamation())->format('Y-m-d H:i:s')                     
                                            );                    
                                        

                            
                                            
                                    
                               $jsonData[$idx++] = $temp; 
                                               
                            }           
                               
                                 
                        }        


                    } 
                }

            }
            return new JsonResponse($jsonData); 
         } else { 
            return $this->render('vol/showVolApreparer.html.twig'); 
         } 
 
        


        
    
    }






     /**
     * @Route("/vol/reclamations_traites_vols_a_preparer", name="admin_affiche_reclamations_traites_vols_a_preparer")
     */
    public function affiche_reclamations_traites_vols_a_preparer(Request $request)
    {
    
        $vols = $this->getDoctrine() 
        ->getRepository(Vol::class) 
        ->findAll();

        if ($request->isXmlHttpRequest() ) {  
            $jsonData = array();  
            $idx = 0;  
            foreach($vols as $vol) { 

                $tves=$vol->getTves();
                $verif=true;
                foreach($tves as $tve){
                    if( !is_null($tve->getDateDebutTache()) ){
                        $verif=false;
                    }
                }


                if($verif){
             
                    if( is_null($vol->getRetard()) ){

                        foreach($vol->getTves() as $tve){
                            if( !is_null($tve->getReclamation()) and (($tve->getReclamation())->getTraite()) ){
                            
                                    $temp = array(
                                        'num_vol'  => ($tve->getVol())->getNumVol(),
                                        'tache' => ($tve->getTache())->getDescription(),  
                                        'date_debut_tache' => $tve->getDateDebutTache(),                    
                                        'cin' => ($tve->getEmploye())->getCin(),
                                        'nom_employe' => ($tve->getEmploye())->getNomEmploye(),
                                        'reclamation' => ($tve->getReclamation())->getDescription(),
                                        'id_rec'=> ($tve->getReclamation())->getId(),
                                        'date_rec'=> (($tve->getReclamation())->getDateReclamation())->format('Y-m-d H:i:s')                     
                                            );                    
                                        
        
                                        if( !is_null($tve->getDateDebutTache()) ){
                                                $temp['date_debut_tache'] = ($tve->getDateDebutTache())->format('Y-m-d H:i:s');
                                         }

                                       
                                            
                                    
                               $jsonData[$idx++] = $temp; 
                                               
                            }           
                               
                                 
                        }        


                    } 
                }

            }
            return new JsonResponse($jsonData); 
         } else { 
            return $this->render('vol/showVolApreparer.html.twig'); 
         } 
 
        
    
    }

     

    /**
     * @Route("/vol/taches/{id}", name="admin_affiche_taches")
     */
    public function affiche_taches($id ,Request $request)
    {
    
        $vol = $this->getDoctrine() 
        ->getRepository(Vol::class) 
        ->find($id);

        if ($request->isXmlHttpRequest() ) {  
            $jsonData = array();  
            $idx = 0;  
            foreach($vol->getTves() as $tve) { 
            
                  $temp = array(
                  'cin_employe'  => ($tve->getEmploye())->getCin(),
                  'nom_employe'  => ($tve->getEmploye())->getNomEmploye(),
                  'tache'  => ($tve->getTache())->getDescription(),
                  'date_debut_tache' => $tve->getDateDebutTache(),  
                  'date_fin_tache' => $tve->getDateFinTache(),
                  'reclamation' => $tve->getReclamation()
                  );          
                        if( !is_null($tve->getDateDebutTache()) ){
                            $temp['date_debut_tache'] = ($tve->getDateDebutTache())->format('Y-m-d H:i:s');
                        } 
                        if( !is_null($tve->getDateFinTache()) ){
                            $temp['date_fin_tache'] = ($tve->getDateFinTache())->format('Y-m-d H:i:s');
                        }   
                        
                        if( !is_null($tve->getReclamation())){
                            $temp['reclamation'] = ($tve->getReclamation())->getDescription();
                        }
                        

                    $jsonData[$idx++] = $temp;  
                
            }
            return new JsonResponse($jsonData); 
         } else { 
            return $this->render('vol/afficheVolsEnCours.html.twig'); 
         } 
        
    
    }




    









     /**
     * @Route("/vol/creation_vol", name="admin_creation_vol")
     * @Route("/vol/modifier_vol/{id}", name="admin_modification_vol")
     */
    public function creer_modifier_vol(Vol $vol=null, Request $request, ObjectManager $manager)
    {
        
        if(!$vol){
          $vol= new Vol();
        }

         // $form = $this->createFormBuilder($vol)
           //          ->add('num_vol' , IntegerType::class, [
           //               'attr' =>[
             //                   'placeholder' => "Numéro du vol" ,
             //                   'class' => 'form_control'
             //             ] 
               //       ])
                //    ->add('date_arrivee')
                //     ->add('date_depart')
                //     ->add('aeroport_arrivee')
                //     ->add('aeroport_depart')
                //     ->add('aeroports_escales')
                //     ->add('duree_au_sol')
                //     ->add('avion')
                //     ->add('tves')
                //     ->add('save' , SubmitType::class, [
                //        'label'=>'Enregistrer'
                //    ])
                //     ->getForm();


                $form = $this->createForm(VolType::class, $vol);

                $form->handleRequest($request);

                if($form->isSubmitted() && $form->isValid()){
                     $manager->persist($vol);
                     $manager->flush();

                     return $this->redirectToRoute('admin_affiche_vols_a_preparer');
                        
                }

        
        return $this->render('vol/creerVol.html.twig' ,[
            'formVol' => $form->createView(),
            'editMode' => $vol->getId() !== null
        ]);
          
    }



    /**
     * @Route("/admin/vol/supprimer_vol/{idvol}", name="admin_supprimer_vol")
     */
    public function supprimer_vol($idvol ,Request $request, ObjectManager $manager)
    {
        $vol = $this->getDoctrine() 
        ->getRepository(Vol::class) 
        ->find($idvol);

        $manager->remove($vol);
        $manager->flush();
               

        return $this->render('vol/showVolAPreparer.html.twig'); 
    }

      /**
     * @Route("/vol/modifier_intervention/{id}", name="modifier_intervention")
     */
    public function modifier_intervention(VolRepository $VolRepository ,$id , Request $request)
    {
    
        $entityManager = $this->getDoctrine()->getManager();
          $vol= $VolRepository->find($id);

          $desc = $request->request->get('inter');
          ($vol->getIntervention())->setDescription($desc);
          
        
          $entityManager->persist($vol);
          $entityManager->flush();

        return $this->redirectToRoute('admin_affiche_vols_passes' );
    }


    /**
     * @Route("/vol/ajouter_intervention/{id}", name="ajouter_intervention")
     */
    public function ajouter_intervention(VolRepository $VolRepository ,$id , Request $request)
    {
    
        $entityManager = $this->getDoctrine()->getManager();
           $vol= $VolRepository->find($id);


          $intervention =new Intervention();
          $desc = $request->request->get('inter');
          $intervention->setDescription($desc);

          $vol->setIntervention($intervention);
          
        
          $entityManager->persist($vol);
          $entityManager->flush();

        return $this->redirectToRoute('admin_affiche_vols_passes');
    }


      /**
     * @Route("/vol/supprimer_intervention/{id}", name="supprimer_intervention")
     */
    public function supprimer_intervention(VolRepository $VolRepository ,$id , Request $request)
    {
    
          $entityManager = $this->getDoctrine()->getManager();
          $vol= $VolRepository->find($id);
          
         $vol->setIntervention(null);
        //  $intervention=$vol->getIntervention();
          
        
       //   $entityManager->remove($intervention);
       $entityManager->persist($vol);
          $entityManager->flush();

        return $this->redirectToRoute('admin_affiche_vols_passes' );
    }



    /**
     * @Route("/admin/telecharger_pdf_details_vol_a_p/{id}", name="telechargement_pdf_details_vols_a_p")
     */
    public function generate_pdf_details_vol_a_p($id , VolRepository $VolRepository)
    {

           //    return $this->render('employe/list.html.twig', [
           // 'employe' => $employe , 
       // ]);
       $entityManager = $this->getDoctrine()->getManager();
       $vol= $VolRepository->find($id);


       $monTemplate = $this->renderView('vol/volAPDetails.html.twig', [
        'vol' => $vol ,
       ]);

            $html2pdf = new Html2Pdf('P', 'A4', 'fr');
            $html2pdf->writeHTML($monTemplate);
            $html2pdf->output('monpdf'.'.pdf', 'D');
            
            return $html2pdf;
        
    }

    /**
     * @Route("/admin/telecharger_pdf_details_vol_p/{id}", name="telechargement_pdf_details_vols_p")
     */
    public function generate_pdf_details_vol_p($id , VolRepository $VolRepository)
    {

           //    return $this->render('employe/list.html.twig', [
           // 'employe' => $employe , 
       // ]);
       $entityManager = $this->getDoctrine()->getManager();
       $vol= $VolRepository->find($id);


       $monTemplate = $this->renderView('vol/volPDetails.html.twig', [
        'vol' => $vol ,
       ]);

            $html2pdf = new Html2Pdf('P', 'A4', 'fr');
            $html2pdf->writeHTML($monTemplate);
            $html2pdf->output('monpdf'.'.pdf', 'D');
            
            return $html2pdf;
        
    }



}
