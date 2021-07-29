<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

use App\Entity\Avion;
use App\Repository\AvionRepository;
use App\Entity\CategorieAvion;
use App\Entity\CategorieAvionRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use App\Form\AvionType;
use App\Form\AvionUploadType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
 
use League\Csv\Reader;
use League\Csv\Statement;
use Spipu\Html2Pdf\Html2Pdf;


class AvionController extends AbstractController
{
    
        /**
     * @Route("/admin/avion/lister_avions", name="lister_avions")
     */
    public function lister_avions(Request $request)
    {
        $avions = $this->getDoctrine() 
            ->getRepository(Avion::class) 
            ->findAll();
      

        if ($request->isXmlHttpRequest() ) {  
            $jsonData = array();  
            $idx = 0;  
            foreach($avions as $avion) { 

                
            
                    $temp = array(
                       'id'  => $avion->getId(),
                       'code_avion'  => $avion->getCodeAvion(),
                       'nom_avion'  => $avion->getNomAvion(),
                       'categorie'  => ($avion->getCategorie())->getCodeCategorie(),
                    );                    

                    $jsonData[$idx++] = $temp;  
                
            }
            return new JsonResponse($jsonData); 
         } else { 
            return $this->render('avion/lister_avions.html.twig'); 
         }

    }


    /**
     * @Route("/admin/avion/creation_avion", name="creation_avion")
     * @Route("/admin/avion/modifier_avion/{id}", name="modification_avion")
     */
    public function creer_modifier_avion(Avion $avion=null, Request $request, ObjectManager $manager)
    {
        
        if(!$avion){
          $avion= new Avion();
        }

    
                $form = $this->createForm(AvionType::class, $avion);

                $form->handleRequest($request);

                if($form->isSubmitted() && $form->isValid()){
                     $manager->persist($avion);
                     $manager->flush();

                     return $this->redirectToRoute('lister_avions');
                        
                }

        
        return $this->render('avion/creerAvion.html.twig' ,[
            'formAvion' => $form->createView(),
            'editMode' => $avion->getId() !== null
        ]);
          
    }


    /**
     * @Route("/admin/avion/supprimer_avion/{id}", name="supprimer_avion")
     */
    public function supprimer_avion($id ,Request $request, ObjectManager $manager)
    {
        $avion = $this->getDoctrine() 
        ->getRepository(Avion::class) 
        ->find($id);

        $manager->remove($avion);
        $manager->flush();
       

        return $this->redirectToRoute('lister_avions');
    }


    /**
     * @Route("/admin/importer_avions", name="importation_avions")
     */
    public function importer_aeroports(Request $request, ObjectManager $manager)
    {
        
           

        $form = $this->createForm(AvionUploadType::class);

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
                        $avion = new Avion();
                        $categorie = $this->getDoctrine() 
                        ->getRepository(CategorieAvion::class) 
                        ->find((int)($row['categorie']));
                        $avion
                            ->setCodeAvion($row['code_avion'])
                            ->setNomAvion($row['nom_avion'])
                            ->setCategorie($categorie)
                        ;
                        $manager->persist($avion);
                        $manager->flush();
                }catch(\Exception $e){
                    return $this->redirectToRoute('lister_avions' ,[
                    'erimport' => 'nn',
                     ]);
                }
            }   

            return $this->redirectToRoute('lister_avions' ,[
                'sucimport' => 'oui',
            ]);
            
        }

          return $this->render('avion/importerAvions.html.twig' ,[
            'formAvion' => $form->createView(),
        ]);

    }


    /**
     * @Route("/admin/telecharger_pdf_list_avions", name="telechargement_pdf_list_avions")
     */
    public function generate_pdf_list_avions()
    {

           
       $avions = $this->getDoctrine() 
       ->getRepository(Avion::class) 
       ->findAll();

       $monTemplate = $this->renderView('avion/listAvions.html.twig', [
        'avions' => $avions ,
       ]);

            $html2pdf = new Html2Pdf('P', 'A4', 'fr');
            $html2pdf->writeHTML($monTemplate);
            $html2pdf->output('monpdf'.'.pdf', 'D');
            
            return $html2pdf;
      


        
    }



}
