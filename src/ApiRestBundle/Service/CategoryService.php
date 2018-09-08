<?php
namespace ApiRestBundle\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;
use FOS\UserBundle\Doctrine\UserManager;
use ApiRestBundle\Service\Util\BaseService;
use AppBundle\Entity\Category;

class CategoryService extends BaseService{

    public function searchAllCategory(){
        $category = $this->repository->findAll();

        return $category;
    }

}