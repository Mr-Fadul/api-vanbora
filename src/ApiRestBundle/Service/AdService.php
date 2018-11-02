<?php
namespace ApiRestBundle\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;
use FOS\UserBundle\Doctrine\UserManager;
use ApiRestBundle\Service\Util\BaseService;
use AppBundle\Entity\Ad;

class AdService extends BaseService{

    public function searchAllAd(){
        $ad = $this->repository->findAll();

        return $ad;
    }

}