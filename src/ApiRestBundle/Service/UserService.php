<?php
namespace ApiRestBundle\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;
use FOS\UserBundle\Doctrine\UserManager;
use ApiRestBundle\Service\Util\BaseService;
use UserBundle\Entity\User;

class UserService extends BaseService{

    public function searchAllUser(){
        $user = $this->repository->findAll();

        return $user;
    }

}