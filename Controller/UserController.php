<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use App\Entity\Advert;

use App\Form\UserType;




class UserController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request, AuthenticationUtils $authUtils)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
        {
            $this->addFlash("alreadylogged","vous êtes déjà connecté !");
            return $this->redirectToRoute('index');
        }

    	    // get the login error if there is one
        $error = $authUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('user/login.html.twig', array(
          'last_username' => $lastUsername,
          'error'         => $error,
    ));

    }

    /**
     * @Route("/signup", name="signup")
     */
    public function signup(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
      $listCategories = [];

      $user = new User();

      $form = $this->createForm(UserType::class, $user);

      $form->handleRequest($request);
      
      if ($form->isSubmitted() && $form->isValid())
      {
                
          $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
          $user->setPassword($password);

          // running upload() before persisting the entities so the url and alt attr 
          // are defined
          $em = $this->getDoctrine()->getManager();
          $em->persist($user);
          $em->flush();
          
          $this->addFlash('signedUpNotice',"Vous êtes bien enregistré. Vous pouvez maintenant vous connecter avec votre nom d'utilisateur et votre mot de passe !");
          return $this->redirectToRoute('login');

      }

        return $this->render('user/signup.html.twig', array(
            'form' => $form->createView(),
            'listCategories' => $listCategories,
        ));

    }
    /**
     * @Route("/myadverts", name="myadverts")
     */
    public function getMyAdverts()
    {
      $user = $this->getUser();
      $userId = $user->getId();

      $em = $this->getDoctrine()->getManager();
      $myAdverts = $em->getRepository(Advert::class)->findBy(['user' => $userId]);

      return $this->render('user/myadverts.html.twig', array(
            'myAdverts' => $myAdverts
        ));

    }

}
