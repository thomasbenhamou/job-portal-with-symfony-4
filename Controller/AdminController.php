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

class AdminController extends Controller
{

    /**
     * @Route("/admin/", name="admin")
     */
    public function admin()
    {
        $em = $this->getDoctrine()->getManager();
        $allAdverts = $em->getRepository(Advert::class)->findAll();
        $allCategories = $em->getRepository(Category::class)->findAll();

        return $this->render('admin/dashboard.html.twig', array(
            'allAdverts' => $allAdverts,
            'allCategories'   => $allCategories,
        ));

    }

    /**
     * @Route("/admin/edit/{id}", name="adminedit")
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

                return $this->redirectToRoute('admin');
            }
            else 
            {
                throw new NotFoundHttpException("Form not validated");
            }
        }

        return $this->render('admin/edit.html.twig', array(
            'form' => $form->createView(),
        ));

    }


    /**
     * @Route("/admin/delete/{id}", name="admindelete")
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
            return $this->redirectToRoute('admin');
        }
    
        return $this->render('admin/delete.html.twig', array(
            'advert' => $advert,
            'form'   => $form->createView(),
        ));

    }
}
