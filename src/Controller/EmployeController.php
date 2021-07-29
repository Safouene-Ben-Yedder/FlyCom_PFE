<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Vol;
use App\Entity\Tahce;
use App\Entity\Avion;
use App\Entity\Aeroport;
use App\Entity\Tve;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Employe;
use App\Entity\CategorieAvion;
use App\Entity\Notification;
use App\Entity\Reclamation;
use App\Repository\EmployeRepository;
use App\Repository\TveRepository;
use App\Repository\TacheRepository;
use App\Repository\NotificationRepository;

use \DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


use App\Form\EmployeType;

use App\Form\ResetPasswordType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use Symfony\Component\Form\FormError;

use Spipu\Html2Pdf\Html2Pdf;




class EmployeController extends AbstractController
{
    /**
     * @Route("/employe/vols_en_cours/{id}", name="employe_show_vols_en_cours")
     */
    public function show_vols_en_cours($id,Request $request)
    {
    
        $employe = $this->getDoctrine() 
        ->getRepository(Employe::class) 
        ->find($id);

        if ($request->isXmlHttpRequest() ) {  
            $jsonData = array();  
            $idx = 0;  
            foreach($employe->getTves() as $tve) { 
             
              if( is_null($tve->getDateFinTache()) ){
                  $temp = array(
                  'id_emp'  => $employe->getId(),
                  'id_tve'  => $tve->getId(),
                  'num_vol' => ($tve->getVol())->getNumVol(),  
                  'aeroport_depart' => (($tve->getVol())->getAeroportDepart())->getNomAeroport(),
                  'aeroport_arrivee' => (($tve->getVol())->getAeroportArrivee())->getNomAeroport(),  
                  'date_arrivee' => (($tve->getVol())->getDateArrivee())->format('Y-m-d H:i:s'),  
                  'date_depart' => (($tve->getVol())->getDateDepart())->format('Y-m-d H:i:s'),  
                  'tache' => ($tve->getTache())->getDescription(),  
                  'date_debut_tache' => $tve->getDateDebutTache(),  
                  'date_fin_tache' => $tve->getDateFinTache(),  
                  'reclamation' => $tve->getReclamation()
                    );                    
                    if( !is_null($tve->getDateDebutTache()) ){
                        $temp['date_debut_tache'] = ($tve->getDateDebutTache())->format('Y-m-d H:i:s');
                    }

                    if( !is_null($tve->getReclamation())){
                        $temp['reclamation'] = ($tve->getReclamation())->getDescription();
                    }
                   


                        $jsonData[$idx++] = $temp;  
                } 
            }
            return new JsonResponse($jsonData); 
         } else { 
            return $this->render('employe/show.html.twig'); 
         } 
        




       // return $this->render('employe/show.html.twig', [
        //    'employe' => $employe , 
       // ]);
    }


 /**
     * @Route("/employe/vols_passes/{id}", name="employe_show_vols_passes")
     */
    public function show_vols_passes($id,Request $request)
    {
    
        $employe = $this->getDoctrine() 
        ->getRepository(Employe::class) 
        ->find($id);

        if ($request->isXmlHttpRequest() ) {  
            $jsonData = array();  
            $idx = 0;  
            foreach($employe->getTves() as $tve) {  
                if( !is_null($tve->getDateFinTache()) ){
                    $temp = array(
                        'id_emp'  => $employe->getId(),
                        'id_tve'  => $tve->getId(),
                        'num_vol' => ($tve->getVol())->getNumVol(),  
                        'aeroport_depart' => (($tve->getVol())->getAeroportDepart())->getNomAeroport(),
                        'aeroport_arrivee' => (($tve->getVol())->getAeroportArrivee())->getNomAeroport(),  
                        'date_arrivee' => (($tve->getVol())->getDateArrivee())->format('Y-m-d H:i:s'),  
                        'date_depart' => (($tve->getVol())->getDateDepart())->format('Y-m-d H:i:s'),  
                        'tache' => ($tve->getTache())->getDescription(),  
                        'date_debut_tache' => ($tve->getDateDebutTache())->format('Y-m-d H:i:s'),  
                        'date_fin_tache' => ($tve->getDateFinTache())->format('Y-m-d H:i:s'), 
                        'reclamation' => $tve->getReclamation()

                    );  
                    
                    if( !is_null($tve->getReclamation())){
                        $temp['reclamation'] = ($tve->getReclamation())->getDescription();
                    }
                    
                    $jsonData[$idx++] = $temp;  

              }
            } 
            return new JsonResponse($jsonData); 
         } else { 
            return $this->render('employe/showVolsPasses.html.twig'); 
         } 
        
       // return $this->render('employe/show.html.twig', [
        //    'employe' => $employe , 
       // ]);
    }



    /**
     * @Route("/employe/telecharger_pdf_taches_AR/{id}", name="telechargement_pdf_taches_AR")
     */
    public function generate_pdf_taches_AR($id)
    {

        $tves=$this->getDoctrine() 
        ->getRepository(Tve::class) 
        ->createQueryBuilder('t')
        ->leftJoin('t.employe', 'a')
           ->where('t.date_debut_tache IS NULL AND a.id='.$id)
           ->getQuery()
           ->getResult();

       $monTemplate = $this->renderView('employe/listTachesAR.html.twig', [
        'tves' => $tves ,
       ]);

            $html2pdf = new Html2Pdf('P', 'A4', 'fr');
            $html2pdf->writeHTML($monTemplate);
            $html2pdf->output('monpdf'.'.pdf', 'D');
            
            return $html2pdf;
              
    }


    /**
     * @Route("/employe/telecharger_pdf_taches_P/{id}", name="telechargement_pdf_taches_P")
     */
    public function generate_pdf_taches_P($id)
    {

        $tves=$this->getDoctrine() 
        ->getRepository(Tve::class) 
        ->createQueryBuilder('t')
        ->leftJoin('t.employe', 'a')
           ->where('t.date_debut_tache IS not NULL AND t.date_fin_tache IS not NULL AND a.id='.$id)
           ->getQuery()
           ->getResult();

       $monTemplate = $this->renderView('employe/listTachesP.html.twig', [
        'tves' => $tves ,
       ]);

            $html2pdf = new Html2Pdf('P', 'A4', 'fr');
            $html2pdf->writeHTML($monTemplate);
            $html2pdf->output('monpdf'.'.pdf', 'D');
            
            return $html2pdf;
      
        
    }


   /**
     * @Route("/employe/inserer_date_debut_tache/{id}/{idtve}", name="inserer_date_debut_tache")
     */
    public function inserer_date_debut_tache(TveRepository $tveRepository ,$id,$idtve)
    {
    
        $entityManager = $this->getDoctrine()->getManager();
          $tve= $tveRepository->find($idtve);

          $tve->setDateDebutTache(new \DateTime());
          $entityManager->flush();

          $vol= $tve->getVol();

           $vol->setTachesCommencees($vol->getTachesCommencees()+1);
            $entityManager->flush();



        return $this->redirectToRoute('employe_show_vols_en_cours', [
            'id' => $id , 
        ]);
    }

      /**
     * @Route("/employe/inserer_date_fin_tache/{id}/{idtve}", name="inserer_date_fin_tache")
     */
    public function inserer_date_fin_tache(TveRepository $tveRepository,TacheRepository $tacheRepository,EmployeRepository $employeRepository ,$id,$idtve)
    {
    
        $entityManager = $this->getDoctrine()->getManager();
          $tve= $tveRepository->find($idtve);


           $tve->setDateFinTache(new \DateTime());
           $entityManager->persist($tve);
           $entityManager->flush();

        
            $vol= $tve->getVol();

            $vol->setTachesTerminees($vol->getTachesTerminees()+1);
            $entityManager->flush();

            

            $catAvion=(($vol->getAvion())->getCategorie())->getCodeCategorie();

            $origin=(($tve->getVol())->getAeroportDepart())->getCodeAeroport();
            $destination=(($tve->getVol())->getAeroportArrivee())->getCodeAeroport();

        
            $t1=$tacheRepository->findOneBy(['code_tache' => 'TA' ]);         
            $tve1 =$tveRepository->findOneBy([ 'tache' => $t1,'vol' => $vol]);

            
            if( !is_null($tve1)){
              if(!is_null($tve1->getDateFinTache())){
                
                   $timeDebut=$tve1->getDateDebutTache();
                   $timeFin=$tve1->getDateFinTache();


                    $dif=$timeDebut->diff($timeFin);
                     $dif2=$dif->format("%H:%I:%S");

                

                    $hour=substr($dif2, 0,2);// we get the firs two values from the hh:mm:ss string
                    $hour=(int)$hour;
                   $hourtomin=$hour*60;// after we have the hour we multiply by 60 to get the min
                    $min=substr($dif2, 3,2);//now we substring 3 to 4 because we want to get only  
                    // the min and we dont want to get the : which is in position 2
                    $min=(int)$min;

                    $dureeT1=$hourtomin+$min;
                   
                }else{
                    $dureeT1=0;
                }     

            }else{
                $dureeT1=0;
            }

            $t2=$tacheRepository->findOneBy(['code_tache' => 'TB' ]);         
            $tve2 =$tveRepository->findOneBy([ 'tache' => $t2,'vol' => $vol]);

            
            if( !is_null($tve2)){
              if(!is_null($tve2->getDateFinTache())){
                
                   $timeDebut=$tve2->getDateDebutTache();
                   $timeFin=$tve2->getDateFinTache();


                    $dif=$timeDebut->diff($timeFin);
                     $dif2=$dif->format("%H:%I:%S");

                

                    $hour=substr($dif2, 0,2);// we get the firs two values from the hh:mm:ss string
                    $hour=(int)$hour;
                   $hourtomin=$hour*60;// after we have the hour we multiply by 60 to get the min
                    $min=substr($dif2, 3,2);//now we substring 3 to 4 because we want to get only  
                    // the min and we dont want to get the : which is in position 2
                    $min=(int)$min;

                    $dureeT2=$hourtomin+$min;
                   
                }else{
                    $dureeT2=0;
                }     

            }else{
                $dureeT2=0;
            }


            $t3=$tacheRepository->findOneBy(['code_tache' => 'TC' ]);         
            $tve3 =$tveRepository->findOneBy([ 'tache' => $t3,'vol' => $vol]);

            
            if( !is_null($tve3)){
              if(!is_null($tve3->getDateFinTache())){
                
                   $timeDebut=$tve3->getDateDebutTache();
                   $timeFin=$tve3->getDateFinTache();


                    $dif=$timeDebut->diff($timeFin);
                     $dif2=$dif->format("%H:%I:%S");

                

                    $hour=substr($dif2, 0,2);// we get the firs two values from the hh:mm:ss string
                    $hour=(int)$hour;
                   $hourtomin=$hour*60;// after we have the hour we multiply by 60 to get the min
                    $min=substr($dif2, 3,2);//now we substring 3 to 4 because we want to get only  
                    // the min and we dont want to get the : which is in position 2
                    $min=(int)$min;

                    $dureeT3=$hourtomin+$min;
                   
                }else{
                    $dureeT3=0;
                }     

            }else{
                $dureeT3=0;
            }

            $t4=$tacheRepository->findOneBy(['code_tache' => 'TD' ]);         
            $tve4 =$tveRepository->findOneBy([ 'tache' => $t4,'vol' => $vol]);

            
            if( !is_null($tve4)){
              if(!is_null($tve4->getDateFinTache())){
                
                   $timeDebut=$tve4->getDateDebutTache();
                   $timeFin=$tve4->getDateFinTache();


                    $dif=$timeDebut->diff($timeFin);
                     $dif2=$dif->format("%H:%I:%S");

                

                    $hour=substr($dif2, 0,2);// we get the firs two values from the hh:mm:ss string
                    $hour=(int)$hour;
                   $hourtomin=$hour*60;// after we have the hour we multiply by 60 to get the min
                    $min=substr($dif2, 3,2);//now we substring 3 to 4 because we want to get only  
                    // the min and we dont want to get the : which is in position 2
                    $min=(int)$min;

                    $dureeT4=$hourtomin+$min;
                   
                }else{
                    $dureeT4=0;
                }     

            }else{
                $dureeT4=0;
            }


            $t5=$tacheRepository->findOneBy(['code_tache' => 'TE' ]);         
            $tve5 =$tveRepository->findOneBy([ 'tache' => $t5,'vol' => $vol]);

            
            if( !is_null($tve5)){
              if(!is_null($tve5->getDateFinTache())){
                
                   $timeDebut=$tve5->getDateDebutTache();
                   $timeFin=$tve5->getDateFinTache();


                    $dif=$timeDebut->diff($timeFin);
                     $dif2=$dif->format("%H:%I:%S");

                

                    $hour=substr($dif2, 0,2);// we get the firs two values from the hh:mm:ss string
                    $hour=(int)$hour;
                   $hourtomin=$hour*60;// after we have the hour we multiply by 60 to get the min
                    $min=substr($dif2, 3,2);//now we substring 3 to 4 because we want to get only  
                    // the min and we dont want to get the : which is in position 2
                    $min=(int)$min;

                    $dureeT5=$hourtomin+$min;
                   
                }else{
                    $dureeT5=0;
                }     

            }else{
                $dureeT5=0;
            }



            $pyscript = 'D:\essai python27\es3.py';
            $python = 'C:\Users\MSI\anaconda3\python.exe';

            $cmd = exec("$python $pyscript $catAvion $origin $destination $dureeT1 $dureeT2 $dureeT3 $dureeT4 $dureeT5");

            if($cmd = "1"){
                if(is_null($vol->getRetardPrediction())){
                    $notification=new Notification();
                    $notification->setDescription("Le vol numéro ".$vol->getNumVol()." peut être en retard ");
                    $notification->setDateNotification(new \DateTime());
                    $notification->setVu(false);
                    $notification->setVol($vol);
                    $entityManager->persist($notification); $entityManager->flush();
                    $vol->setRetardPrediction(true) ;  $entityManager->flush();
                }
                            
            }else{
                $vol->setRetardPrediction(null);
            }


            if($vol->getTachesTerminees()== count($vol->getTves()) ){
                if($tve->getDateFinTache() > $vol->getDateDepart()){
                    $vol->setRetard(true);$entityManager->flush();
                    $r=1;
                    
                }
                else{
                    $vol->setRetard(false);$entityManager->flush();
                    $r=0;
                }

                $list = array (
                    array($catAvion,$origin,$destination,$dureeT1,$dureeT2,$dureeT3,$dureeT4,$dureeT5,$r)
                  );
                  
                  $file = fopen("C:/Users/imen/Desktop/PFE/essai python27/flightdataset3.csv","a");
                  
                  foreach ($list as $line) {
                    fputcsv($file, $line);
                  }
                  
                  fclose($file);
                
            }
            
            
        return $this->redirectToRoute('employe_show_vols_en_cours', [
            'id' => $id , 
        ]);
    }



    /**
     * @Route("/vol/ajouter_reclamation/{id}/{idtve}", name="ajouter_reclamation")
     */
    public function ajouter_reclamation(TveRepository $TveRepository ,$idtve ,$id, Request $request)
    {
    
        $entityManager = $this->getDoctrine()->getManager();
           $tve= $TveRepository->find($idtve);


          
          $rec = $request->request->get('reclamation');
          $reclamation=new Reclamation();
          $reclamation->setDescription($rec);
          $reclamation->setDateReclamation(new \DateTime());
          $reclamation->setTraite(false);
          $tve->setReclamation($reclamation);


        
          $entityManager->persist($reclamation);
          $entityManager->flush();

          return $this->redirectToRoute('employe_show_vols_en_cours', [
            'id' => $id , 
        ]);
       
    }



    /**
     * @Route("/vol/modifier_reclamation/{id}/{idtve}", name="modifier_reclamation")
     */
    public function modifier_reclamation(TveRepository $TveRepository ,$idtve ,$id, Request $request)
    {
    
        $entityManager = $this->getDoctrine()->getManager();
           $tve= $TveRepository->find($idtve);


          
          $rec = $request->request->get('reclamation');
          ($tve->getReclamation())->setDescription($rec);


    
          $entityManager->flush();

          return $this->redirectToRoute('employe_show_vols_en_cours', [
            'id' => $id , 
        ]);
       
    }

      /**
     * @Route("/gestion_comptes", name="gestion_comptes")
     */
    public function gerer_comptes(Request $request)
    {
    
        $employes = $this->getDoctrine() 
        ->getRepository(Employe::class) 
        ->findBy([ 'etat' => false]);

        if ($request->isXmlHttpRequest() ) {  
            $jsonData = array();  
            $idx = 0;  
            foreach($employes as $employe) { 
             
              
                  $temp = array(
                  'id_emp'  => $employe->getId(),
                  'cin' => $employe->getCin(),
                  'date_naissance' => ($employe->getDateDeNaissance())->format('Y-m-d'),
                  'nom_employe' => $employe->getNomEmploye()                
                    );                    
                        $jsonData[$idx++] = $temp;  
                 
            }
            return new JsonResponse($jsonData); 
         } else { 
            return $this->render('employe/gestionComptes.html.twig'); 
         } 
        

       // return $this->render('employe/show.html.twig', [
        //    'employe' => $employe , 
       // ]);
    }




     /**
     * @Route("activer_compte/{idcpt}", name="activer_compte")
     */
    public function activer_compte(EmployeRepository $employeRepository ,$idcpt, Request $request,\Swift_Mailer $mailer)
    {
    
        $entityManager = $this->getDoctrine()->getManager();
           $compte= $employeRepository->find($idcpt);

          $compte->setEtat(true);

          $entityManager->flush();

          $message = (new \Swift_Message('Compte activé'))
          ->setFrom('Papercut@papercut.com')
          ->setTo($compte->getEmail())
          ->setBody(
              "Votre compte a ete activé",
              'text/html'
          );

       $mailer->send($message);

          return $this->redirectToRoute('gestion_comptes');
       
    }

     /**
     * @Route("refuser_compte/{idcpt}", name="refuser_compte")
     */
    public function refuser_compte(EmployeRepository $employeRepository ,$idcpt, Request $request,\Swift_Mailer $mailer)
    {
    
        $entityManager = $this->getDoctrine()->getManager();
           $compte= $employeRepository->find($idcpt);

          
           $message = (new \Swift_Message('Compte activé'))
           ->setFrom('Papercut@papercut.com')
           ->setTo($compte->getEmail())
           ->setBody(
               "Votre demande d'inscription a ete rejeté",
               'text/html'
           );
 
          $mailer->send($message);


           $entityManager->remove($compte);
          $entityManager->flush();

          return $this->redirectToRoute('gestion_comptes');
       
    }

    /**
     * @Route("/modifier_profil/{id}", name="modifier_profil")
     */
    public function modifier_profil(EmployeRepository $employeRepository ,$id, Request $request,UserPasswordEncoderInterface $encoder)
    {
    
        $entityManager = $this->getDoctrine()->getManager();
        $employe= $employeRepository->find($id);

        $mdp=$employe->getMotDePasse();
            
        $employe->setMotDePasse($mdp);

        $employe->setConfirmMdp($mdp);

        $form = $this->createForm(EmployeType::class, $employe);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

        /*    $hash=$encoder->encodePassword($employe, $employe->getMotDePasse());
            $employe->setMotDePasse($hash);*/

          if(!is_null($form->get('photo')->getData())){
            $photo = $form->get('photo')->getData();

            // On génère un nouveau nom de fichier
            $fichier = md5(uniqid()).'.'.$photo->guessExtension();


            $photo->move(
                $this->getParameter('images_directory'),
                $fichier
            );

            $employe->setPhoto($fichier);
          }

            $entityManager->flush();

            if($employe->getTypeEmploye()=== 1)
             return $this->redirectToRoute('employe_show_vols_en_cours',array('id' => $id));
           else
             return $this->redirectToRoute('admin_affiche_vols_en_cours'); 
                        
        }



        if($employe->getTypeEmploye()=== 1)
          return $this->render('employe/modifier_profil_employe.html.twig' ,[
            'formProfil' => $form->createView(),
        ]);
        else
          return $this->render('employe/modifier_profil_admin.html.twig' ,[
            'formProfil' => $form->createView(),
        ]);
       
    }



     /**
     * @Route("/employe/changer_mot_de_passe/{id}", name="changer_mot_de_passe")
     */
    public function changer_mot_de_passe(EmployeRepository $employeRepository ,$id, Request $request,UserPasswordEncoderInterface $encoder)
    {
    
        $entityManager = $this->getDoctrine()->getManager();
        $employe= $employeRepository->find($id);

        

        $form = $this->createForm(ResetPasswordType::class);

    	$form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){



            //$oldPasswordE = $encoder->encodePassword($employe,$form->get('oldPassword')->getData());

            //$oldPasswordV = $employe->getMotDePasse();

           
            $oldPassword = $form->get('oldPassword')->getData();
            
             // Si l'ancien mot de passe est bon

             if ($encoder->isPasswordValid($employe, $oldPassword)) {

                $hash=$encoder->encodePassword($employe, $form->get('plainPassword')->getData());
                $employe->setMotDePasse($hash);

                

                $entityManager->flush();

                
                if($employe->getTypeEmploye()=== 1)
                    return $this->redirectToRoute('employe_show_vols_en_cours',array('id' => $id));
                 else
                    return $this->redirectToRoute('admin_affiche_vols_en_cours'); 

            } else {

                $form->addError(new FormError('Ancien mot de passe incorrect'));

            }


         


        }
        if($employe->getTypeEmploye()=== 1)
          return $this->render('employe/changer_mdp_employe.html.twig' ,[
            'formMdp' => $form->createView(),
        ]);
        else
          return $this->render('employe/changer_mdp_admin.html.twig' ,[
            'formMdp' => $form->createView(),
        ]);
       
    }
    



     /**
     * @Route("/admin/gestion_employes", name="gestion_employes")
     */
    public function gerer_employes(Request $request)
    {
    
        $employes = $this->getDoctrine() 
        ->getRepository(Employe::class) 
        ->findBy([ 'etat' => true , 'type_employe'  => 1]);

        if ($request->isXmlHttpRequest() ) {  
            $jsonData = array();  
            $idx = 0;  
            foreach($employes as $employe) {  
                if( $employe->getEtat() ){
                    $temp = array(
                        'id_emp'  => $employe->getId(),
                        'cin'  =>  $employe->getCin(),
                        'date_de_naissance' =>  ($employe->getDateDeNaissance())->format('Y-m-d'),  
                        'nom' => $employe->getNomEmploye(),
                        'active' =>''

                    );  

                if( $employe->getActive() ){
                    $temp['active'] = 'oui';  
                }else{
                    $temp['active'] = 'non';
                }


                    
                    $jsonData[$idx++] = $temp;  

              }
            } 
            return new JsonResponse($jsonData); 
         } else { 
            return $this->render('employe/gestionEmployes.html.twig'); 
         } 
        
      
    }


     /**
     * @Route("/admin/activer_compte_ex/{idemp}", name="activer_compte_ex")
     */
    public function activer_compte_ex(EmployeRepository $employeRepository ,$idemp)
    {
    
        $entityManager = $this->getDoctrine()->getManager();
           $employe= $employeRepository->find($idemp);

          $employe->setActive(true);

          $entityManager->flush();

          return $this->redirectToRoute('gestion_employes');
       
    }

     /**
     * @Route("/admin/desactiver_compte_ex/{idemp}", name="desactiver_compte_ex")
     */
    public function desactiver_compte_ex(EmployeRepository $employeRepository ,$idemp)
    {
    
        $entityManager = $this->getDoctrine()->getManager();
           $employe= $employeRepository->find($idemp);

          $employe->setActive(false);

          $entityManager->flush();

          return $this->redirectToRoute('gestion_employes');
       
    }


    /**
     * @Route("/admin/supprimer_compte/{idemp}", name="supprimer_compte")
     */
    public function supprimer_compte(EmployeRepository $employeRepository ,$idemp)
    {
    
        $entityManager = $this->getDoctrine()->getManager();
           $employe= $employeRepository->find($idemp);

           $entityManager->remove($employe);
           $entityManager->flush();

          return $this->redirectToRoute('gestion_employes');
       
    }



    /**
     * @Route("/admin/telecharger_pdf_list_employes", name="telechargement_pdf_list_employes")
     */
    public function generate_pdf_list_employes()
    {

           //    return $this->render('employe/list.html.twig', [
           // 'employe' => $employe , 
       // ]);
       $employes = $this->getDoctrine() 
       ->getRepository(Employe::class) 
       ->findAll();

       $monTemplate = $this->renderView('employe/listEmployes.html.twig', [
        'employes' => $employes ,
       ]);

            $html2pdf = new Html2Pdf('P', 'A4', 'fr');
            $html2pdf->writeHTML($monTemplate);
            $html2pdf->output('monpdf'.'.pdf', 'D');
            
            return $html2pdf;
      


        
    }






}
