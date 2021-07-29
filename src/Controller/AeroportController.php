<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

use App\Entity\Aeroport;
use App\Repository\AeroportRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use App\Form\AeroportType;
use App\Form\AeroportUploadType;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use League\Csv\Reader;
use League\Csv\Statement;

use Spipu\Html2Pdf\Html2Pdf;


class AeroportController extends AbstractController
{
    /**
     * @Route("/aeroport/lister_aeroports", name="lister_aeroports")
     */
    public function lister_aeroports(Request $request)
    {
        $aeroports = $this->getDoctrine() 
            ->getRepository(Aeroport::class) 
            ->findAll();
      

        if ($request->isXmlHttpRequest() ) {  
            $jsonData = array();  
            $idx = 0;  
            foreach($aeroports as $aeroport) { 

                
            
                    $temp = array(
                       'id'  => $aeroport->getId(),
                       'nom_aeroport'  => $aeroport->getNomAeroport(),
                       'code_aeroport'  => $aeroport->getCodeAeroport(),
                    );                    

                    $jsonData[$idx++] = $temp;  
                
            }
            return new JsonResponse($jsonData); 
         } else { 
            return $this->render('aeroport/lister_aeroports.html.twig'); 
         }

    }




     /**
     * @Route("/vol/creation_aeroport", name="creation_aeroport")
     * @Route("/admin/aeroport/modifier_aeroport/{id}", name="modification_aeroport")
     */
    public function creer_modifier_aeroport(Aeroport $aeroport=null, Request $request, ObjectManager $manager)
    {
        
        if(!$aeroport){
          $aeroport= new Aeroport();
        }

    
                $form = $this->createForm(AeroportType::class, $aeroport);

                $form->handleRequest($request);

                if($form->isSubmitted() && $form->isValid()){
                     $manager->persist($aeroport);
                     $manager->flush();

                     return $this->redirectToRoute('lister_aeroports');
                        
                }

        
        return $this->render('aeroport/creerAeroport.html.twig' ,[
            'formAeroport' => $form->createView(),
            'editMode' => $aeroport->getId() !== null
        ]);
          
    }




     /**
     * @Route("/admin/importer_aeroports", name="importation_aeroports")
     */
    public function importer_aeroports(Request $request, ObjectManager $manager)
    {
        
           

        $form = $this->createForm(AeroportUploadType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $fichier = $form->get('fichier')->getData();

            $nom = md5(uniqid()).'.csv';


            $fichier->move(
                $this->getParameter('csv_directory'),
                $nom
            );


            $reader = Reader::createFromPath($this->getParameter('csv_directory').'/'.$nom, 'r');
           
            $offset = 0;
            $results = $reader->fetchAssoc($offset);
            
           // $keys = ['code_aeroport', 'nom_aeroport'];
           // $results = $reader->fetchAssoc($keys);
           
            foreach ($results as $row){
                try{  
                        $aeroport = new Aeroport();
                        $aeroport
                            ->setCodeAeroport((int)($row['code_aeroport']))
                            ->setNomAeroport($row['nom_aeroport'])

                        ;
                        $manager->persist($aeroport);
                        $manager->flush();
                }catch(\Exception $e){
                    return $this->redirectToRoute('lister_aeroports' ,[
                    'erimport' => 'nn',
                     ]);
                }
            }   

            return $this->redirectToRoute('lister_aeroports' ,[
                'sucimport' => 'oui',
            ]);
            
        }

          return $this->render('aeroport/importerAeroports.html.twig' ,[
            'formAeroport' => $form->createView(),
        ]);

    }



    /**
     * @Route("/admin/aeroport/supprimer_aeroport/{id}", name="supprimer_aeroport")
     */
    public function supprimer_aeroport($id ,Request $request, ObjectManager $manager)
    {
        $aeroport = $this->getDoctrine() 
        ->getRepository(Aeroport::class) 
        ->find($id);

        $manager->remove($aeroport);
        $manager->flush();
       

        return $this->redirectToRoute('lister_aeroports' ,[
            'sucsupp' => 'oui',
        ]);
    }


    /**
     * @Route("/admin/telecharger_pdf_list_aeroports", name="telechargement_pdf_list_aeroports")
     */
    public function generate_pdf_list_aeroports()
    {

           
       $aeroports = $this->getDoctrine() 
       ->getRepository(Aeroport::class) 
       ->findAll();

       $monTemplate = $this->renderView('aeroport/listAeroports.html.twig', [
        'aeroports' => $aeroports ,
       ]);

            $html2pdf = new Html2Pdf('P', 'A4', 'fr');
            $html2pdf->writeHTML($monTemplate);
            $html2pdf->output('monpdf'.'.pdf', 'D');
            
            return $html2pdf;
      


        
    }



}

