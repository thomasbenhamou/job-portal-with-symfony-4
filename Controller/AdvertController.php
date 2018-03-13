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

        $nbPages = ceil(count($listAdverts) / $nbPerPage);

        // if ($page > $nbPages)
        // {
        //     throw $this->createNotFoundException("Page ".$page." does not exists.");
        // }


        return $this->render('advert/index.html.twig', array(
            'listAdverts' => $listAdverts,
            'nbPages'     => $nbPages,
            'page'        => $page,
            ));
    }
    
    /**
     * @Route("/search/", name="search")
     */
    public function search(Request $request)
    {  
        if ($request->isMethod('POST'))
        {
            $search = $request->request->get('search');
            $em = $this->getDoctrine()->getManager();
            $listResults = $em->getRepository(Advert::class)->getAdsSearched($search);
            return $this->render('advert/search_results.html.twig',array(
                'search' => $search,
                'listResults' => $listResults,
            ));
        }
        return $this->redirectToRoute('index');


    }

    /**
     * @Route("/category/{id}", name="category")
     */
    public function category($id)
    {
       
        $em = $this->getDoctrine()->getManager();

        $adsByCategory = $em->getRepository(Advert::class)->getAdsByCategory($id);
        $category = $em->getRepository(Category::class)->find($id);

        if (!$adsByCategory)
        {
                $this->addFlash('noads',"Désolé, il n'y a pas encore d'annonces dans cette catégorie");
                return $this->redirectToRoute('index');
        
        }

        return $this->render('advert/category.html.twig', [
            'adsByCategory' => $adsByCategory,
            'category' => $category
        ]);

    }

    /**
     * @Route("/view/{id}", name="view")
     */
    public function view(Advert $advert, $id)
    {
       
    //     Typehint in the function parameters automatically fetches 
    //     the advert with the corresponding id

    //     $em = $this->getDoctrine()->getManager();

    //     $advert = $em->getRepository(Advert::class)->find($id);


    //     if (!$advert) {
    //         throw $this->createNotFoundException(
    //         'No advert found for id '.$id
    //     );
    // }

        return $this->render('advert/view.html.twig', [
            'advert' => $advert,
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
            $this->addFlash('notConnected', 'Connectez-vous pour publier une annonce, ou inscrivez-vous!');
            return $this->redirectToRoute('login');

        } else 

        {

        $em = $this->getDoctrine()->getManager();

        // Creating the advert
        $advert = new Advert;
        $advert->setUser($user);

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

                $this->addFlash('publishedNotice','Votre annonce a été publiée!');

                return $this->redirectToRoute('view', array(
                    'id' => $advert->getId()
                ));
            }

        }
        return $this->render('advert/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    }
    /**
     * @Route("/edit/{id}", name="edit")
     */
    public function edit($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();


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

                $this->addFlash('editedNotice', "L'annonce a bien été modifiée !");

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
        ));

    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Advert $advert, Request $request)
    {
        
        $em = $this->getDoctrine()->getManager();

        // On crée un formulaire vide, qui ne contiendra que le champ CSRF
        // Cela permet de protéger la suppression d'annonce contre cette faille
        $form = $this->get('form.factory')->create();

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid())
        {
            $em->remove($advert);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', "L'annonce a bien été supprimée.");
            $this->addFlash('deleteNotice', "L'annonce a bien été supprimée !");
            return $this->redirectToRoute('myadverts');
        }
    
        return $this->render('advert/delete.html.twig', array(
            'advert' => $advert,
            'form'   => $form->createView(),
        ));

    }


}
