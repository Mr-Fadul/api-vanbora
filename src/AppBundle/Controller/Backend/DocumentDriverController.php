<?php

namespace AppBundle\Controller\Backend;

use AppBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Form\DocumentDriverType;
use AppBundle\Entity\DocumentDriver;

/**
 * DocumentDriver controller
 *
 * @Route("/backend/documents")
 */
class DocumentDriverController extends BaseController{

    /**
     * Lists all Drivers entities.
     *
     * @Route("/", name="backend_document_driver", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
            $em = $this->getDoctrine()->getManager();

            $entities = $em->getRepository('AppBundle:DocumentDriver')->findAll();

            return array(
              'entities' => $entities,
          );
        } else {
            throw $this->createNotFoundException('Você não tem permissão para acessar esta tela.');
        }
    }

    /**
     * Displays a form to edit an existing Driver entity.
     *
     * @Route("/{id}/edit", name="backend_document_driver_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('AppBundle:DocumentDriver')->find($id);

            return array(
            'entity'      => $entity
        );
        } else {
            throw $this->createNotFoundException('Você não tem permissão para acessar esta tela.');
        }
    }

    /**
     * Edits an existing Driver entity.
     *
     * @Route("/{id}", name="backend_document_driver_update")
     * @Method("PUT")
     * @Template("AppBundle:Driver:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('AppBundle:DocumentDriver')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Driver entity.');
            }
            try {
                if($entity->getStatus() == 0){
                  $entity->setStatus(1);
                  $entity->setPendingDescription(NULL);
                  $entity->setIsPending(0);
                  $this->sendEmail('Vanbora - Documentação aprovada', $email, '
                      <div style="width: 100%; background: #baebf7; padding: 10px 0;display:block;float:none;margin: 0 auto;">
                      <img src="https://beta.vanbora.today/bundles/app/frontend/img/logo.png" alt="Vanbora" style="width:80px; height:80px;margin: -6px auto;margin-top: -6px;margin-right: auto;margin-bottom: -6px;margin-left: auto;display:block;"/>
                      </div>
                      <div style="width: 39%; background: #fff; padding: 0 60px;display:block;float:none;margin: 0 auto;margin-top:45px;">
                      <h1 style="color: #020d16; font-weight:500; font-size: 18px;margin: -30px -45px 0 0;text-align:center;font-weight:600;">Documentação aprovada com sucesso!</h1>
                      <p style="color: #666666; font-weight: 400; font-size:16px;margin: 20px 0 0 0;text-align:left;line-height:24px;">Olá, '.$entity->getFirstName().'.<br><br></p>
                      <p style="color: #666666; font-weight: 400; font-size:13px;margin:0;text-align:left;line-height:24px;">
                      A sua documentação foi aprovada com sucesso e você já pode oferecer caronas para publico, seja bem-vindo!.<br><br></p>
                      <p style="color: #666666; font-weight: 400; font-size:15px;margin: 20px 0;text-align:left;">Um abraço,<br><b>Equipe Vanbora.</b></p>
                      <p style="color: #666666; font-weight: 400; font-size:16px;margin: 20px 0;text-align:center;">Em caso de dúvida, entre em contato conosco.<br><a style="color: #666666;" href="mailto:contato@vanbora.today">contato@vanbora.today</a> </p>

                      </div>
                  '); 
                }else{
                  $entity->setStatus(0);
                  $this->sendEmail('Vanbora - Documentação suspensa', $email, '
                      <div style="width: 100%; background: #baebf7; padding: 10px 0;display:block;float:none;margin: 0 auto;">
                      <img src="https://beta.vanbora.today/bundles/app/frontend/img/logo.png" alt="Vanbora" style="width:80px; height:80px;margin: -6px auto;margin-top: -6px;margin-right: auto;margin-bottom: -6px;margin-left: auto;display:block;"/>
                      </div>
                      <div style="width: 39%; background: #fff; padding: 0 60px;display:block;float:none;margin: 0 auto;margin-top:45px;">
                      <h1 style="color: #020d16; font-weight:500; font-size: 18px;margin: -30px -45px 0 0;text-align:center;font-weight:600;">Problemas na sua documentação!</h1>
                      <p style="color: #666666; font-weight: 400; font-size:16px;margin: 20px 0 0 0;text-align:left;line-height:24px;">Olá, '.$entity->getFirstName().'.<br><br></p>
                      <p style="color: #666666; font-weight: 400; font-size:13px;margin:0;text-align:left;line-height:24px;">
                      Reenvie a sua documentação para que seus anúncios voltem, encontramos um problema e deixamos suspenso temporariamente.<br><br></p>
                      <p style="color: #666666; font-weight: 400; font-size:15px;margin: 20px 0;text-align:left;">Um abraço,<br><b>Equipe Vanbora.</b></p>
                      <p style="color: #666666; font-weight: 400; font-size:16px;margin: 20px 0;text-align:center;">Em caso de dúvida, entre em contato conosco.<br><a style="color: #666666;" href="mailto:contato@vanbora.today">contato@vanbora.today</a> </p>

                      </div>
                  '); 
                }
                $em->flush();

                return $this->redirect($this->generateUrl('backend_document_driver', array('id' => $id)));
            } catch (\Exception $e) {
                $request->getSession()
                    ->getFlashBag()
                     ->add('error', 'Ocorreu algum erro inesperado. Tente novamente mais tarde!');
            }

            return array(
                'entity'      => $entity,
                'edit_form'   => $editForm->createView(),
            );
        } else {
            throw $this->createNotFoundException('Você não tem permissão para acessar esta tela.');
        }
    }

    /**
     * @Route("/pending/document/{description}/{id}", name="backend_document_driver_pending", options={"expose"=true})
     */
    public function pendingDocumentAction(Request $request,$description,$id)
    {
        
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:DocumentDriver')->findOneById($id);

        $entity->setStatus(0);
        $entity->setIsPending(1);
        $entity->setPendingDescription($description);
        $em->flush();

        $this->sendEmail('Vanbora - Pendência na documentação', $email, '
            <div style="width: 100%; background: #baebf7; padding: 10px 0;display:block;float:none;margin: 0 auto;">
            <img src="https://beta.vanbora.today/bundles/app/frontend/img/logo.png" alt="Vanbora" style="width:80px; height:80px;margin: -6px auto;margin-top: -6px;margin-right: auto;margin-bottom: -6px;margin-left: auto;display:block;"/>
            </div>
            <div style="width: 39%; background: #fff; padding: 0 60px;display:block;float:none;margin: 0 auto;margin-top:45px;">
            <h1 style="color: #020d16; font-weight:500; font-size: 18px;margin: -30px -45px 0 0;text-align:center;font-weight:600;">Contém pendências na sua documentação!</h1>
            <p style="color: #666666; font-weight: 400; font-size:16px;margin: 20px 0 0 0;text-align:left;line-height:24px;">Olá, '.$entity->getFirstName().'.<br><br></p>
            <p style="color: #666666; font-weight: 400; font-size:13px;margin:0;text-align:left;line-height:24px;">
            Acesse agora mesmo o seu painel e veja o motivo pelo qual não conseguimos lhe aprovar.<br><br></p>
            <p style="color: #666666; font-weight: 400; font-size:15px;margin: 20px 0;text-align:left;">Um abraço,<br><b>Equipe Vanbora.</b></p>
            <p style="color: #666666; font-weight: 400; font-size:16px;margin: 20px 0;text-align:center;">Em caso de dúvida, entre em contato conosco.<br><a style="color: #666666;" href="mailto:contato@vanbora.today">contato@vanbora.today</a> </p>

            </div>
        ');      

        $return = array(
            "responseCode" => 200
        );
        return $this->returnJson($return);
    }

    /**
    * Atualiza o Status do Usuários para ATIVO
    *
    * @Route("/?id={id}", name="backend_document_driver_status")
    */
    public function activateStatusAction($id)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('AppBundle:DocumentDriver')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find DocumentDriver entity.');
            }

            $condicao = $entity->getStatus();
            if ($condicao == "Habilitado") {
                $entity->setEnabled(false);
            } else {
                $entity->setEnabled(true);
            }

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('backend_document_driver', array('id' => $id)));
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