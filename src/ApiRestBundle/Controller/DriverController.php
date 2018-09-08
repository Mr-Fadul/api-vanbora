<?php

namespace ApiRestBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as FOS;
use AppBundle\Entity\Driver;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use JMS\Serializer\SerializationContext;

class DriverController extends Controller
{

    /**
     * @FOS\Get("/list", name="api_rest_driver", options={"method_prefix" = false, "expose"=true })
     * @FOS\View(statusCode=200, serializerEnableMaxDepthChecks=true, serializerGroups={"listagemUsuario"})
     */
    public function searchAllDriverAction(Request $request)
    {
        $service = $this->get('api.service.driver');
        $driver = $service->searchAllDriver();

        $context = new SerializationContext();
        $context->setSerializeNull(true);

        $serializer = $this->get('jms_serializer');

        $response = new Response($serializer->serialize($driver, 'json', $context));
        $response->headers->set('Content-Type', 'application/json');
        
        return $response;
    }
}