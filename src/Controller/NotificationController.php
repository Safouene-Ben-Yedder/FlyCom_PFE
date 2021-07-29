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
use App\Entity\Notification;
use App\Repository\EmployeRepository;
use App\Repository\TveRepository;
use App\Repository\VolRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Dompdf\Dompdf;
use Dompdf\Options;





class NotificationController extends AbstractController
{
    
    /**
     * @Route("/notification/notifications_non_vus", name="notifications_non_vus")
     */
    public function affiche_notifications_non_vus(Request $request)
    {
    
        $notifications = $this->getDoctrine() 
            ->getRepository(Notification::class) 
            ->findBy([ 'vu' => false]);
      

        if ($request->isXmlHttpRequest() ) {  
            $jsonData = array();  
            $idx = 0;  
            foreach($notifications as $notification) { 

                
            
                    $temp = array(
                       'description'  => $notification->getDescription(),
                       'date_notification'  => ($notification->getDateNotification())->format('Y-m-d H:i:s'),
                       'id_vol'  => ($notification->getVol())->getId(),
                    );                    

                    $jsonData[$idx++] = $temp;  
                
            }
            return new JsonResponse($jsonData); 
         } else { 
            return $this->render('vol/afficheVolsEnCours.html.twig'); 
         } 
        
    
    }


     /**
     * @Route("/notification/mettre_notifications_vus", name="mettre_notifications_vus")
     */
    public function mettre_notifications_vus(Request $request, ObjectManager $manager)
    {
    
        
        if ($request->isXmlHttpRequest() ) {  
             $notifications = $this->getDoctrine() 
            ->getRepository(Notification::class) 
            ->findBy([ 'vu' => false]);
           

            foreach($notifications as $notification) {
                $notification->setVu(true);  $manager->flush();;
            }

                $jsonData = array();  
                return new JsonResponse($jsonData); 
         } else { 
            return $this->render('vol/afficheVolsEnCours.html.twig'); 
         } 
        
    
    }




    /**
     * @Route("/notification/lister_notifications", name="lister_notifications")
     */
    public function lister_notifications(Request $request)
    {
    
        
        
        $notifications = $this->getDoctrine() 
        ->getRepository(Notification::class) 
        ->findAll(); 


        if ($request->isXmlHttpRequest() ) {  
            $jsonData = array();  
            $idx = 0;  
            foreach($notifications as $notification) {  
                
                    $temp = array(
                        'description'  => $notification->getDescription(),
                        'date_notification'  => ($notification->getDateNotification())->format('Y-m-d H:i:s'),
                    );   
                    
                    $jsonData[$idx++] = $temp;                
            } 
            return new JsonResponse($jsonData); 
         } else{ 
            return $this->render('notification/afficheNotifications.html.twig'); 
        } 
        
     
    
    }

   




}
