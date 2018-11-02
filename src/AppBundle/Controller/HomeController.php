<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller{
	
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request){
    	$em = $this->getDoctrine()->getManager();

    	$categories = $em->getRepository("AppBundle:Category")->findAll();
    	$ads = $em->getRepository("AppBundle:Ad")->findByActive(1);

        return $this->render('AppBundle:Home:index.html.twig', array('categories' => $categories,'ads' => $ads));
    }

    /**
     * @Route("/anuncios", name="ads")
     */
    public function adsAction(Request $request){
        $em = $this->getDoctrine()->getManager();

        $ads = $em->getRepository("AppBundle:Ad")->findByActive(1);

        return $this->render('AppBundle:Home:ads.html.twig', array('ads' => $ads));
    }

    /**
     * @Route("/categoria/{slug}", name="category_show")
     */
    public function categoryShowAction(Request $request,$slug){
        $em = $this->getDoctrine()->getManager();

        $category = $em->getRepository("AppBundle:Category")->findOneBySlug($slug);

        if(!$category){
            throw $this->createNotFoundException('Categoria inexistente.'); 
        }

        $ads = $em->getRepository("AppBundle:Ad")->findByCategory($category);

        return $this->render('AppBundle:Home:category.html.twig', array('ads' => $ads,'category' => $category));
    }
}
