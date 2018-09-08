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
use JMS\Serializer\SerializerBuilder;

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

        $serializer = SerializerBuilder::create()->build();
        $return = $serializer->serialize($category, 'json');
        return new Response($return, 200, array('Content-Type' => 'application/json'));

    }
}