<?php

namespace ApiRestBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as FOS;
use AppBundle\Entity\Category;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use JMS\Serializer\SerializationContext;

class CategoryController extends Controller
{

    /**
     * @FOS\Get("/list", name="api_rest_category", options={"method_prefix" = false, "expose"=true })
     * @FOS\View(statusCode=200, serializerEnableMaxDepthChecks=true, serializerGroups={"listagemUsuario"})
     */
    public function searchAllCategoryAction(Request $request)
    {
        $service = $this->get('api.service.category');
        $category = $service->searchAllCategory();

        $context = new SerializationContext();
        $context->setSerializeNull(true);

        $serializer = $this->get('jms_serializer');

        $response = new Response($serializer->serialize($category, 'json', $context));
        $response->headers->set('Content-Type', 'application/json');
        
        return $response;
    }
}