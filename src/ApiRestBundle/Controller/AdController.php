<?php

namespace ApiRestBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as FOS;
use AppBundle\Entity\Ad;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;

class AdController extends Controller
{

    /**
     * @FOS\Get("/list", name="api_rest_ad", options={"method_prefix" = false, "expose"=true })
     * @FOS\View(statusCode=200, serializerEnableMaxDepthChecks=true, serializerGroups={"listagemUsuario"})
     */
    public function searchAllAdAction(Request $request)
    {
        $service = $this->get('api.service.ad');
        $ad = $service->searchAllAd();

        $serializer = SerializerBuilder::create()->build();
        $return = $serializer->serialize($ad, 'json');
        return new Response($return, 200, array('Content-Type' => 'application/json'));

    }
}