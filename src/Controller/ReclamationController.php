<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ReclamationRepository;
use App\Entity\Reclamation;

class ReclamationController extends AbstractController
{
    /**
     * @Route("/admin/reclamation/traiter_reclamation/{idrec}/{p}", name="traiter_reclamation")
     */
    public function traiter_reclamation(ReclamationRepository $reclamationRepository ,$idrec, $p)
    {
    
        $entityManager = $this->getDoctrine()->getManager();
          $reclamation= $reclamationRepository->find($idrec);

          $reclamation->setTraite(true);
          $entityManager->flush();

        if($p == 1){
          return $this->redirectToRoute('admin_affiche_vols_en_cours');
        }else{
          return $this->redirectToRoute('admin_affiche_vols_a_preparer');
        }
    }


}
