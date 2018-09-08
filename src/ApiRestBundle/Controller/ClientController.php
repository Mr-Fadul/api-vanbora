<?php

namespace ApiRestBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as FOS;
use AppBundle\Entity\Client;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use JMS\Serializer\SerializationContext;

class ClientController extends Controller
{

    /**
     * @FOS\Get("/list", name="api_rest_client", options={"method_prefix" = false, "expose"=true })
     * @FOS\View(statusCode=200, serializerEnableMaxDepthChecks=true, serializerGroups={"listagemUsuario"})
     */
    public function searchAllClientAction(Request $request)
    {
        $service = $this->get('api.service.client');
        $client = $service->searchAllClient();

        $context = new SerializationContext();
        $context->setSerializeNull(true);

        $serializer = $this->get('jms_serializer');

        $response = new Response($serializer->serialize($client, 'json', $context));
        $response->headers->set('Content-Type', 'application/json');
        
        return $response;
    }
}