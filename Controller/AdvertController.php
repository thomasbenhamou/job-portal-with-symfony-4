<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Advert;
use App\Entity\Image;
use App\Entity\Category;
use App\Form\AdvertType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class AdvertController extends Controller
{
    /**
     * @Route("/index/{page}", name="index")
     *         
     */
    public function index($page = 1)
    {

        $nbPerPage = 6;

        $em = $this->getDoctrine()->getManager();

        $listAdverts = $em->getRepository(Advert::class)->getAllAds($page, $nbPerPage);

        $listCategories = $em->getRepository(Category::class)->findAll();

        $nbPages = ceil(count($listAdverts) / $nbPerPage);

        if ($page > $nbPages)
        {
            throw $this->createNotFoundException("Page ".$page." does not exists.");
        }

        return $this->render('advert/index.html.twig', array(
            'listAdverts' => $listAdverts,
            'nbPages'     => $nbPages,
            'page'        => $page,
            'listCategories' => $listCategories
            ));
    }
    
    /**
     * @Route("/category/{id}", name="category")
     */
    public function category($id)
    {
       
        $em = $this->getDoctrine()->getManager();

        $adsByCategory = $em->getRepository(Advert::class)->getAdsByCategory($id);
        $listCategories = $em->getRepository(Category::class)->findAll();


        if (!$adsByCategory)
        {
            throw $this->createNotFoundException(
            'No category found for id '.$id
        );
        }


        return $this->render('advert/category.html.twig', [
            'adsByCategory' => $adsByCategory,
            'listCategories' => $listCategories
        ]);

    }

    /**
     * @Route("/view/{id}", name="view")
     */
    public function view($id)
    {
       
        $em = $this->getDoctrine()->getManager();

        $advert = $em->getRepository(Advert::class)->find($id);
        $listCategories = $em->getRepository(Category::class)->findAll();


        if (!$advert) {
            throw $this->createNotFoundException(
            'No advert found for id '.$id
        );
    }

        return $this->render('advert/view.html.twig', [
            'advert' => $advert,
            'listCategories' => $listCategories
        ]);

    }
    

    /**
     * @Route("/add/", name="add")
     */
    public function add(Request $request)
    {
        
        $user = $this->getUser();

        if (null === $user) {
        
        // Ici, l'utilisateur est anonyme ou l'URL n'est pas derrière un pare-feu
            return $this->redirectToRoute('login');

        } else 

        {

        $em = $this->getDoctrine()->getManager();
        $listCategories = $em->getRepository(Category::class)->findAll();

        // Creating the advert
        $advert = new Advert;

        // Creating the form and passing it the advert
        $form = $this->createForm(AdvertType::class, $advert);
        
        if ($request->isMethod('POST'))
        {
            $form->handleRequest($request);

            if ($form->isValid())
            {
                // running upload() before persisting the entities so the url and alt attr 
                // are defined
                $em = $this->getDoctrine()->getManager();
                $em->persist($advert);
                $em->flush();

                return $this->redirectToRoute('view', array(
                    'id' => $advert->getId()
                ));
            }

        }
        return $this->render('advert/add.html.twig', array(
            'form' => $form->createView(),
            'listCategories' => $listCategories
        ));
    }

    }
    /**
     * @Route("/edit/{id}", name="edit")
     */
    public function edit($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $listCategories = $em->getRepository(Category::class)->findAll();


        $advert = $this->getDoctrine()
            ->getManager()
            ->getRepository(Advert::class)
            ->find($id)
        ;

        if (!$advert) {
        throw $this->createNotFoundException(
            'No advert found for id '.$id
        );
        }  

        $form = $this->createForm(AdvertType::class, $advert);


        if ($request->isMethod('POST'))
        {
            $form->handleRequest($request);

            if ($form->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                $em->persist($advert);
                $em->flush();

                $request->getSession()->getFlashBag()->add('notice', 'Your advert has been published');

                return $this->redirectToRoute('view', array(
                    'id' => $advert->getId()
                ));
            }
            else 
            {
                throw new NotFoundHttpException("Form not validated");
            }
        }

        return $this->render('advert/edit.html.twig', array(
            'form' => $form->createView(),
            'listCategories' => $listCategories
        ));

    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete($id, Request $request)
    {
        
        $em = $this->getDoctrine()->getManager();
        $listCategories = $em->getRepository(Category::class)->findAll();

        $advert = $this->getDoctrine()
            ->getManager()
            ->getRepository(Advert::class)
            ->find($id)
        ;

        if (!$advert) 
        {
            throw $this->createNotFoundException(
                'No advert found for id '.$id
            );
        }

        // On crée un formulaire vide, qui ne contiendra que le champ CSRF
        // Cela permet de protéger la suppression d'annonce contre cette faille
        $form = $this->get('form.factory')->create();

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid())
        {
            $em->remove($advert);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', "L'annonce a bien été supprimée.");

            return $this->redirectToRoute('index');
        }
    
        return $this->render('advert/delete.html.twig', array(
            'advert' => $advert,
            'form'   => $form->createView(),
            'listCategories' => $listCategories
        ));

    }


}
