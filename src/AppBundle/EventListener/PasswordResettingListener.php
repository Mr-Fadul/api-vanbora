<?php

namespace AppBundle\EventListener;

use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class PasswordResettingListener implements EventSubscriberInterface{

    private $router;
    private $security;

    public function __construct(UrlGeneratorInterface $router, AuthorizationChecker $security){
        $this->router = $router;
        $this->security = $security;
    }

    public static function getSubscribedEvents(){
        return [
            FOSUserEvents::RESETTING_RESET_SUCCESS => 'onPasswordResettingSuccess',
        ];
    }

    public function onPasswordResettingSuccess(FormEvent $event){
        $url = $this->router->generate('dashboard_index');
        $event->setResponse(new RedirectResponse($url));
    }
}