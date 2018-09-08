<?php

namespace AppBundle\Security\User\Provider;

use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Facebook;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use AppBundle\Entity\Client;
use \BaseFacebook;
use \FacebookApiException;

class FacebookProvider implements UserProviderInterface
{
    /**
     * @var \Facebook
     */
    protected $facebook;
    protected $userManager;
    protected $entityManager;
    protected $validator;
    protected $container;

    public function __construct(BaseFacebook $facebook, $userManager, $entityManager, $validator, $container)
    {
        $this->facebook = $facebook;

        // Add this to not have the error "the ssl certificate is invalid."
        Facebook::$CURL_OPTS[CURLOPT_SSL_VERIFYPEER] = false;
        Facebook::$CURL_OPTS[CURLOPT_SSL_VERIFYHOST] = 2;

        $this->userManager = $userManager;
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->container = $container;
    }

    public function supportsClass($class)
    {
        return $this->userManager->supportsClass($class);
    }

    public function findUserByFbId($fbId)
    {
        return $this->userManager->findUserBy(array('facebookId' => $fbId));
    }

    public function findUserByUsername($username)
    {
        return $this->userManager->findUserBy(array('username' => $username));
    }

    public function connectExistingAccount()
    {
        try {
            $fbdata = $this->facebook->api('/me');
        } catch (FacebookApiException $e) {
            $fbdata = null;
            return false;
        }

        $alreadyExistingAccount = $this->findUserByFbId($fbdata['id']);

        if (!empty($alreadyExistingAccount)) {
            return false;
        }

        if (!empty($fbdata)) {
            $currentUserObj = $this->container->get('security.context')->getToken()->getUser();

            $user = $this->findUserByUsername($currentUserObj->getUsername());

            if (empty($user)) {
                return false;
            }

            $user->setFBData($fbdata);

            if (count($this->validator->validate($user, 'Facebook'))) {
                // TODO: the user was found obviously, but doesnt match our expectations, do something smart
                throw new UsernameNotFoundException('The facebook user could not be stored');
            }
            $this->userManager->updateUser($user);

            return true;
        }

        return false;
    }

    public function loadUserByUsername($username)
    {
        $logger = $this->container->get("logger");

        try {
            $fbdata = $this->facebook->api('/'.$username."?fields=email,name,first_name,last_name");
        } catch (FacebookApiException $e) {
            $fbdata = null;
            $logger->critical($e);
        }
        $logger->critical(serialize($fbdata));

        $user = $this->userManager->findUserBy(array('facebookId' => $fbdata['id']));

        if (!empty($fbdata)) {
            if (empty($user)) {
                $user = $this->userManager->findUserBy(array('email' => $fbdata['email']));
                if (empty($user)) {
                    $user = new Client();
                    // $user = $this->userManager->createUser();
                    $user->setEnabled(true);
                    $user->setUsername($username);
                    $user->setFacebookId($fbdata['id']);
                    $user->setPlainPassword(rand(100000, 999999));
                    $user->setUserKey(crc32($user->getEmail().date('Y-m-d H:i:s')));
                }
            }

            $user->setFBData($fbdata);

            if (count($this->validator->validate($user, 'Facebook'))) {
                // TODO: the user was found obviously, but doesnt match our expectations, do something smart
                throw new UsernameNotFoundException('The facebook user could not be stored');
            }
            // $this->userManager->updateUser($user);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }

        if (empty($user)) {

            // TODO: the user was found obviously, but doesnt match our expectations, do something smart
            throw new UsernameNotFoundException('The facebook user could not be stored');
        }

        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$this->supportsClass(get_class($user)) || !$user->getFacebookId()) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getFacebookId());
    }
}
