<?php
namespace ApiRestBundle\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;
use FOS\UserBundle\Doctrine\UserManager;
use ApiRestBundle\Service\Util\BaseService;
use AppBundle\Entity\Client;

class ClientService extends BaseService{

    public function searchAllClient(){
        $client = $this->repository->findAll();

        return $client;
    }

}