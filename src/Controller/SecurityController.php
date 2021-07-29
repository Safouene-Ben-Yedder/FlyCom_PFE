<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Form\RegistrationType;
use App\Repository\EmployeRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class SecurityController extends AbstractController
{

     /**
     * @Route("/authentification", name="authentification")
     */
    public function authentifier(AuthenticationUtils $authenticationUtils)
    {

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
                    
        return $this->render('security/authentification.html.twig');
        
  
    }

     /**
     * @Route("/login_success", name="login_success")
     */
    public function postLoginRedirectAction(EmployeRepository $employeRepository)
    {
         
        
        $employe=$this->getUser();

        $type=$employe->getTypeEmploye();


        $id=$employe->getId();


        if($type === 1 and !$employe->getEtat()){

            return $this->redirectToRoute('authentification',[
                'cptNA' => "oui",
            ]);
        }

        if($type === 1 and !$employe->getActive()){

            return $this->redirectToRoute('authentification',[
                'cptDes' => "oui",
            ]);
        }

        if ($type === 1  and $employe->getEtat()) {
            return $this->redirectToRoute("employe_show_vols_en_cours" ,array('id' => $id));
        } else{
             return $this->redirectToRoute("admin_affiche_vols_en_cours");
         }
    }

    /**
     * @Route("/inscription", name="inscription")
     */
    public function sinscrire(Request $request,ObjectManager $manager,
    UserPasswordEncoderInterface $encoder)
    {
        $employe=new Employe();

        $form= $this->createForm(RegistrationType::class , $employe);   
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            $hash=$encoder->encodePassword($employe, $employe->getMotDePasse());
            $employe->setMotDePasse($hash);

            $employe->addRole("ROLE_USER");

            $manager->persist($employe);
            $manager->flush();

            return $this->redirectToRoute('authentification',[
                'inscri' => "oui",
            ]);
        }
        
        return $this->render('security/registration.html.twig' ,[
            'form' => $form->createView()
        ]);
        
  
    }


     /**
     * @Route("/deconnexion", name="deconnexion")
     */
    public function deconnecter(){}


    /**
     * @Route("/forgotten_password", name="app_forgotten_password")
     */
    public function forgottenPassword(
        Request $request,
        UserPasswordEncoderInterface $encoder,
        \Swift_Mailer $mailer,
        TokenGeneratorInterface $tokenGenerator
    ): Response
    {

        if ($request->isMethod('POST')) {

            $email = $request->request->get('email');

            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(Employe::class)->findOneByEmail($email);
            /* @var $user Employe */

            if ($user === null) {
                $this->addFlash('danger', 'Email Inconnu');
                return $this->redirectToRoute("authentification",[
                    'emailNV' => "oui",
                ]);
            }
            $token = $tokenGenerator->generateToken();

            try{
                $user->setResetToken($token);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash('warning', $e->getMessage());
                return $this->redirectToRoute("authentification");
            }

            $url = $this->generateUrl('app_reset_password', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);

            $message = (new \Swift_Message('Mot de passe oublié'))
                ->setFrom('Papercut@papercut.com')
                ->setTo($user->getEmail())
                ->setBody(
                    "Clique que le lien suivant pour réinitialiser votre mot de passe : " . $url,
                    'text/html'
                );

            $mailer->send($message);

            $this->addFlash('notice', 'Mail envoyé');

            return $this->redirectToRoute("authentification",[
                'emailEnvoye' => "oui",
            ]);
        }

        return $this->render('security/forgotten_password.html.twig');
    }


    /**
     * @Route("/reset_password/{token}", name="app_reset_password")
     */
    public function resetPassword(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder)
    {

        if ($request->isMethod('POST')) {
            $entityManager = $this->getDoctrine()->getManager();

            $user = $entityManager->getRepository(Employe::class)->findOneBy(['resetToken' => $token]);
            /* @var $user Employe */

            if ($user === null) {
                $this->addFlash('danger', 'Token Inconnu');
                return $this->redirectToRoute('authentification',[
                    'erEn' => "oui",
                ]);
            }

            $user->setResetToken(null);
            $user->setMotDePasse($passwordEncoder->encodePassword($user, $request->request->get('password')));
            $entityManager->flush();

            $this->addFlash('notice', 'Mot de passe mis à jour');

            return $this->redirectToRoute('authentification',[
                'mdpCh' => "oui",
            ]);
        }else {

            return $this->render('security/reset_password.html.twig', ['token' => $token]);
        }

    }
    



}
