<?php

namespace AppBundle\Controller\Backend;

use AppBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class HomeController extends BaseController{

    /**
     * @Route("/backend", name= "backend_home")
     * @Template()
     */
    public function indexAction(Request $request){

        return array(
            //...
        );
    }

}