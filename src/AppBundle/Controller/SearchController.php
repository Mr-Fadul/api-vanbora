<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use AppBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SearchController extends BaseController
{

    /**
     * @Route("/buscar/{query}", name="search")
     */
    public function searchAction(Request $request,$query = null)
    {   
        $query = $request->query->get('query');

        $em = $this->getDoctrine()->getManager();

        $ads = $em->getRepository('AppBundle:Ad')->findForSearch($query);

        return $this->render('AppBundle:Home:ads.html.twig', array(
            'ads' => $ads
        ));
    }
}
