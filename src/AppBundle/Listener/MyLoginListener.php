<?php

namespace AppBundle\Listener;

use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * Custom login listener.
 */
class MyLoginListener
{
    private $userManager;

    public function __construct(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();

        if($user->getLogged()){
            throw new AuthenticationException('this user is already logged');
        }else{
            $user->setLogged(true);
            $this->userManager->updateUser($user);
        }
    }
}
