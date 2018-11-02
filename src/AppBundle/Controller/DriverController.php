<?php

namespace AppBundle\Controller;

use AppBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Form\DriverType;
use AppBundle\Entity\Driver;


class DriverController extends BaseController{

    /**
     * Lists all Drivers entities.
     *
     * @Route("/motoristas", name="driver")
     * @Method("GET")
     * @Template("AppBundle:Driver:index.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository("AppBundle:Category")->findAll();

        $drivers = $em->getRepository('AppBundle:Driver')->findByEnabled(1);

        return array(
          'drivers' => $drivers,
          'categories' => $categories
        );
    }

    /**
     * Lists all Drivers entities.
     *
     * @Route("/motorista/{id}", name="driver_show")
     * @Method("GET")
     * @Template("AppBundle:Home:ads.html.twig")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $ads = $em->getRepository('AppBundle:Ad')->findByDriver($id);

        return array(
          'ads' => $ads,
        );
    }
}