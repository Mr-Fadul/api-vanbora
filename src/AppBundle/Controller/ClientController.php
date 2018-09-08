<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use AppBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use AppBundle\Form\ClientType;
use AppBundle\Entity\Client;

class ClientController extends BaseController{

    /**
    * @Route("/cadastre-se", name="client_signup")
    */
    public function signUpAction(Request $request)
    {
        $entity = new Client();
        $error = null;
        if ($request->getMethod() == 'POST') {
            if ($this->validUsernameEmail($entity)) {
                try {
                    $em = $this->getDoctrine()->getManager();

                    $email = $request->request->get('email');
                    $firstName = $request->request->get('firstName');
                    $lastName = $request->request->get('lastName');
                    $cpf = $request->request->get('cpf');
                    $celphone = $request->request->get('celphone');
                    
                    $this->requestCustomForm($request, $entity);

                    $entity->setUsername($email);
                    $entity->setEmail($email);
                    $entity->setCpf($cpf);
                    $entity->setFirstName($firstName);
                    $entity->setLastName($lastName);
                    $entity->setCelphone($celphone);
                    $entity->setEnabled(true);
                    $entity->setRoles(array("ROLE_USER"));

                    $em->persist($entity);
                    $em->flush();

                    //$request->getSession()->getFlashBag()->add('success', 'Registro criado com sucesso!');

                    $this->sendEmail('Vanbora - Confirmação de cadastro', $email, '
                            <div style="width: 100%; background: #020d16; padding: 10px 0;display:block;float:none;margin: 0 auto;">
                            <img src="https://vanbora.today/bundles/app/frontend/img/logo.png" alt="Vanbora" style="margin: 0 auto;display:block;"/>
                            </div>
                            <div style="width: 39%; background: #fff; padding: 0 60px;display:block;float:none;margin: 0 auto;margin-top:45px;">
                            <h1 style="color: #020d16; font-weight:500; font-size: 18px;margin: -30px -45px 0 0;text-align:center;font-weight:600;">Seu cadastro foi efetuado com sucesso!</h1>
                            <p style="color: #666666; font-weight: 400; font-size:16px;margin: 20px 0 0 0;text-align:left;line-height:24px;">Bem vindo ao Vanbora, '.$entity->getFirstName().'.<br><br></p>
                            <p style="color: #666666; font-weight: 400; font-size:13px;margin:0;text-align:left;line-height:24px;">
                            Agora ficou mais fácil a sua volta para casa, <br>baixe o nosso app e tenha acesso rapidamente.<br><br></p>
                            <p style="color: #666666; font-weight: 400; font-size:15px;margin: 20px 0;text-align:left;">Um abraço,<br><b>Equipe Vanbora.</b></p>
                            <p style="color: #666666; font-weight: 400; font-size:16px;margin: 20px 0;text-align:center;">Em caso de dúvida, entre em contato conosco.<br><a style="color: #666666;" href="mailto:contato@vanbora.today">contato@vanbora.today</a> </p>

                            </div>
                    ');

                    $token = new UsernamePasswordToken($entity, null, 'homepage', array('ROLE_USER'));
                    $this->get('security.context')->setToken($token);

                    return $this->redirectToRoute('homepage');
                } catch (\Exception $e) {
                    $error = 'Ocorreu um erro'.$e;
                    $this->log($e);
                    //$this->errorMessage($request);
                }
            } else {
                $request->getSession()->getFlashBag()->add('error', 'Email já cadastrado, tente novamente!');
            }
        }

        return $this->render('AppBundle:Client:index.html.twig', array(
            'client' => $entity,
            'error' => $error,
        ));
    }

    /**
    * Set entity fields custom
    * @param Request $request
    * @param Entity $entity
    */
    public function requestCustomForm($request, $entity)
    {
        $em = $this->getDoctrine()->getManager();
        $clientCustomArray = $request->request->get('client_custom');

        if (!empty($clientCustomArray['plainPassword'])) {
            $encoder = $this->container->get('security.encoder_factory')->getEncoder($entity);
            $new_pwd_encoded = $encoder->encodePassword($clientCustomArray['plainPassword'], $entity->getSalt());
            $entity->setPassword($new_pwd_encoded);
        }
    }

    private function sendEmail($titulo, $email, $message)
    {
        $mailMessage = \Swift_Message::newInstance()
              ->setSubject($titulo)
              ->setFrom("contato@vanbora.today")
              ->setTo($email)
              ->setContentType("text/html")
              ->setBody($message);

        $this->get('mailer')->send($mailMessage);
    }

    /**
    * @Route("/user/couple/facebook", name="user_couple_fb_with_account")
    */
    public function connectFacebookWithAccountAction()
    {
        $fbService = $this->get('fos_facebook.user.login');
        //todo: check if service is successfully connected.
        $fbService->connectExistingAccount();
        return $this->redirect($this->generateUrl('fos_user_profile_edit'));
    }

    /**
    * @Route("/facebook/login_check", name="_security_check_facebook")
    */
    public function loginFbAction(Request $request)
    {
        $plainId = $request->query->get('planid');
        if ($plainId == 'free') {
            return $this->redirect($this->generateUrl("homepage"));
        }

        return $this->redirect($this->generateUrl("sign_inscription", array('plainId'=>$plainId)));
    }

    /**
     * Controller for FB login
     * @Route("/facebook/login", name="_security_login")
     */
    public function loginAction()
    {
        return $this->redirect($this->generateUrl('_security_login'));
    }
}
