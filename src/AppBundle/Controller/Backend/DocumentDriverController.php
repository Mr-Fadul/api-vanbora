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
                }else{
                  $entity->setStatus(0);
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