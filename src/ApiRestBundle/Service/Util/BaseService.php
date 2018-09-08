<?php
namespace ApiRestBundle\Service\Util;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use FOS\UserBundle\Doctrine\UserManager;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Doctrine\Common\Persistence\ObjectRepository;
use UserBundle\Entity\User;

abstract class BaseService{

    /**
     * @var EntityManager
     * $em Doctrine EntityManager
    */
    protected $em;

    /**
     * @var FormFactory
     *
     * $formFactory Symfony Form Factory
    */
    protected $formFactory;

    /**
     * @var TokenStorage
     *
     * $tokenStorage Symfony TokenStorage
    */
    protected $tokenStorage;

    /**
     * @var UserManager
     *
     * $userManager Symfony UserManager
    */
    protected $userManager;

    /**
     * @var EmailService
     *
     * $emailService EmailService
    */
    protected $emailService;

    /**
     * @var ObjectRepository|null
     *
     * $repository ObjectRepository
    */
    protected $repository = null;

    /**
     * @var Array
     *
     * $errors
    */
    protected $errors = array();

    public function setEntityManager(EntityManager $em){
        $this->em = $em;

        return $this;
    }

    public function setFormFactory(FormFactory $formFactory){
        $this->formFactory = $formFactory;

        return $this;
    }

    public function setTokenStorage(TokenStorage $tokenStorage){
        $this->tokenStorage = $tokenStorage;

        return $this;
    }

    public function setUserManager(UserManager $userManager){
        $this->userManager = $userManager;

        return $this;
    }

    public function setEmailService(EmailService $emailService){
        $this->emailService = $emailService;

        return $this;
    }

    public function setRepository($repository){
        $this->repository = $this->em->getRepository($repository);

        return $this;
    }

    public function getErrors(){
        return $this->errors;
    }

    protected function formataerrorsForm(\Symfony\Component\Form\Form $form){
        $errors = array();
        foreach ($form->getErrors() as $erro) {

            if ($form->isRoot()) {
                $errors['#'][] = $erro->getMessage();
            } else {
                $errors[$form->createView()->vars['full_name']][] = $erro->getMessage();
            }
        }
        foreach ($form->all() as $filho) {
            if (!$filho->isValid()) {
                $errors = array_merge($errors, $this->formataerrorsForm($filho));
            }
        }
        $this->errors = $errors;

        return $errors;
    }

    public function getFormErrors(\Symfony\Component\Form\Form $form){
        if ($form instanceof \Symfony\Component\Form\Form){
            foreach ($form->getErrors() as $error){
                $errors[] = self::$translator->trans($error->getMessage(), array(), 'validators');
            }
            foreach ($form->all() as $key => $child){
                if($child instanceof \Symfony\Component\Form\Form){
                    $err = self::getFormErrors($child);
                    if (count($err) > 0){
                        $this->errors = array_merge($errors, $err);
                    }
                }
            }
        }

        return $this->errors;
    }

    public function childErrors(\Symfony\Component\Form\Form $form){
        $errors = array();
        foreach ($form->getErrors() as $error){
            $message = $error->getMessage();
            array_push($errors, $message);
        }

        return $errors;
    }

    public function formatCpfCnpj($string){
        $count = strlen($string);

        if($count == 14){
            $string = substr($string, 0, 2) . '.' . substr($string, 2, 3) .
                '.' . substr($string, 5, 3) . '/' . substr($string, 8, 4) .
                '-' . substr($string, 12, 2);
        }elseif ($count == 11){
            $string = substr($string, 0, 3) . '.' . substr($string, 3, 3) .
                '.' . substr($string, 6, 3) . '-' . substr($string, 9, 2);
        }

        return $string;
    }

    public function formatCpfCnpjToInt($string){
        $string = str_replace(".", "", $string);
        $string = str_replace("-", "", $string);
        $string = str_replace("/", "", $string);

        $count = strlen($string);
        if($count != 11 && $count != 14){
            return false;
        }

        return $string;
    }

    public function formatZipCode($string){
        $string = str_replace("-", "", $string);

        $count = strlen($string);
        if($count != 8){
            return null;
        }

        return $string;
    }

    public function getUser(){
        return $this->tokenStorage->getToken()->getUser();
    }

    public function getUserId(){
        return is_null($this->getUser()) ? null : $this->getUser()->getId();
    }

    public function findUserById($id){
        $user = $this->userManager->findUserBy(array('id'=>$id));
        return $user;
    }

    public function findUserByUsername($username){
        $user = $this->userManager->findUserBy(array('username'=>$username));
        return $user;
    }

    public function findUserByEmail($email){
        $user = $this->userManager->findUserBy(array('email'=>$email));
        return $user;
    }

}