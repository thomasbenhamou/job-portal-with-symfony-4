<?php 

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Category;


class CategoriesController extends Controller
{
	/**
     * 
     *         
     */
	public function getAllCategories()
	{
		$em = $this->getDoctrine()->getManager();
		$listCategories = $em->getRepository(Category::class)->findAll();

		return $this->render('categories/categories.html.twig', array(
			'listCategories' => $listCategories
		));

	}


}