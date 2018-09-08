<?php

namespace AppBundle\Listener;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\DependencyInjection\Container;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;

class LoginListener{
    

    private $securityContext;

    private $em;

    private $container;

    private $doc;

    public function __construct(SecurityContext $securityContext, Doctrine $doctrine, Container $container){
        $this->securityContext = $securityContext;
        $this->doc = $doctrine;
        $this->em              = $doctrine->getManager();
        $this->container        = $container;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event){
        if ($this->securityContext->isGranted('IS_AUTHENTICATED_FULLY')){
            // user has just logged in
        }

        if ($this->securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')){
            // user has logged in using remember_me cookie
        }

        // First get that user object so we can work with it
        $user = $event->getAuthenticationToken()->getUser();

        // Get the current session and associate the user with it
        $sessionId = $this->container->get('session')->getId();
        $user->setSessionId($sessionId);
        $this->em->persist($user);
        $this->em->flush();

    }
}
