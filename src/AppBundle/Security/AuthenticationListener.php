<?php

namespace AppBundle\Security;

use Symfony\Component\Security\Core\AuthenticationEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Event\AuthenticationEvent;
use Symfony\Component\HttpFoundation\RequestStack;
use FOS\UserBundle\Model\UserManagerInterface;

class AuthenticationListener implements EventSubscriberInterface
{
    private $requestStack;
    private $userManager;

    public function __construct(RequestStack $requestStack, UserManagerInterface $userManager)
    {
        $this->requestStack = $requestStack;
        $this->userManager = $userManager;
    }

    public static function getSubscribedEvents()
    {
        return array(
            AuthenticationEvents::AUTHENTICATION_SUCCESS => 'onAuthenticationSuccess',
        );
    }

    public function onAuthenticationSuccess(AuthenticationEvent $event)
    {
        $token = $event->getAuthenticationToken();
        $sessionId = $this->requestStack->getMasterRequest()->getSession()->getId();
        if($token->getUser() !=null){
            $activeSessId = $token->getUser()->getSessionId();

            if ($activeSessId && $sessionId !== $activeSessId) {
                $token->setAuthenticated(false); // Sets the authenticated flag.
            }    
        }
    }
}
