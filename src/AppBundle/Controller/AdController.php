<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Ad controller
 *
 * @Route("/anuncio")
 */
class AdController extends Controller{
	
    /**
     * @Route("/{slug}", name="ad")
     */
    public function indexAction(Request $request){
    	$em = $this->getDoctrine()->getManager();

        return $this->render('AppBundle:Ad:index.html.twig');
    }
}