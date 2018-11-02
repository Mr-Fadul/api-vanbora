<?php

namespace AppBundle\Controller\Backend;

use AppBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Entity\Ad;

/**
 * AdController controller
 *
 * @Route("/backend/ads")
 */
class AdController extends BaseController{

    /**
     * Lists all Ads entities.
     *
     * @Route("/", name="backend_ads", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
            $em = $this->getDoctrine()->getManager();

            $entities = $em->getRepository('AppBundle:Ad')->findAll();

            return array(
              'entities' => $entities,
          );
        } else {
            throw $this->createNotFoundException('Você não tem permissão para acessar esta tela.');
        }
    }

    /**
    * Atualiza o Status do Anúncios para ATIVO
    *
    * @Route("/?id={id}", name="backend_ads_status")
    */
    public function activateStatusAction($id)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('AppBundle:Ad')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Ad entity.');
            }

            $condicao = $entity->getActive();
            if ($condicao == 1) {
                $entity->setActive(0);
                $this->sendEmail('Vanbora - Anúncio desativado', $entity->getDriver()->getEmail(), '
                      <div style="width: 100%; background: #baebf7; padding: 10px 0;display:block;float:none;margin: 0 auto;">
                      <img src="https://beta.vanbora.today/bundles/app/frontend/img/logo.png" alt="Vanbora" style="width:80px; height:80px;margin: -6px auto;margin-top: -6px;margin-right: auto;margin-bottom: -6px;margin-left: auto;display:block;"/>
                      </div>
                      <div style="width: 39%; background: #fff; padding: 0 60px;display:block;float:none;margin: 0 auto;margin-top:45px;">
                      <h1 style="color: #020d16; font-weight:500; font-size: 18px;margin: -30px -45px 0 0;text-align:center;font-weight:600;">Seu anúncio foi desativado!</h1>
                      <p style="color: #666666; font-weight: 400; font-size:16px;margin: 20px 0 0 0;text-align:left;line-height:24px;">Olá, '.$entity->getDriver()->getFirstName().'.<br><br></p>
                      <p style="color: #666666; font-weight: 400; font-size:13px;margin:0;text-align:left;line-height:24px;">
                      Seu anúncio "'.$entity->getName().'" foi desativado, encontramos um problema com o mesmo.<br><br></p>
                      <p style="color: #666666; font-weight: 400; font-size:15px;margin: 20px 0;text-align:left;">Um abraço,<br><b>Equipe Vanbora.</b></p>
                      <p style="color: #666666; font-weight: 400; font-size:16px;margin: 20px 0;text-align:center;">Em caso de dúvida, entre em contato conosco.<br><a style="color: #666666;" href="mailto:contato@vanbora.today">contato@vanbora.today</a> </p>

                      </div>
                  ');
            } else {
                $entity->setActive(1);
                $this->sendEmail('Vanbora - Anúncio publicado', $entity->getDriver()->getEmail(), '
                      <div style="width: 100%; background: #baebf7; padding: 10px 0;display:block;float:none;margin: 0 auto;">
                      <img src="https://beta.vanbora.today/bundles/app/frontend/img/logo.png" alt="Vanbora" style="width:80px; height:80px;margin: -6px auto;margin-top: -6px;margin-right: auto;margin-bottom: -6px;margin-left: auto;display:block;"/>
                      </div>
                      <div style="width: 39%; background: #fff; padding: 0 60px;display:block;float:none;margin: 0 auto;margin-top:45px;">
                      <h1 style="color: #020d16; font-weight:500; font-size: 18px;margin: -30px -45px 0 0;text-align:center;font-weight:600;">Anúncio aprovado com sucesso!</h1>
                      <p style="color: #666666; font-weight: 400; font-size:16px;margin: 20px 0 0 0;text-align:left;line-height:24px;">Olá, '.$entity->getDriver()->getFirstName().'.<br><br></p>
                      <p style="color: #666666; font-weight: 400; font-size:13px;margin:0;text-align:left;line-height:24px;">
                      Seu anúncio "'.$entity->getName().'" foi publicado com sucesso.<br><br></p>
                      <p style="color: #666666; font-weight: 400; font-size:15px;margin: 20px 0;text-align:left;">Um abraço,<br><b>Equipe Vanbora.</b></p>
                      <p style="color: #666666; font-weight: 400; font-size:16px;margin: 20px 0;text-align:center;">Em caso de dúvida, entre em contato conosco.<br><a style="color: #666666;" href="mailto:contato@vanbora.today">contato@vanbora.today</a> </p>

                      </div>
                  ');
            }

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('backend_ads', array('id' => $id)));
        } else {
            throw $this->createNotFoundException('Você não tem permissão para acessar esta tela.');
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

}