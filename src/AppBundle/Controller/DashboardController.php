<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use AppBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use AppBundle\Form\DocumentDriverType;
use AppBundle\Entity\DocumentDriver;

/**
 * Dashboard controller
 *
 * @Route("/painel")
 */
class DashboardController extends BaseController{

    /**
    * @Route("/", name="dashboard_index")
    */
    public function indexAction(Request $request)
    {
        return $this->render('AppBundle:Dashboard:index.html.twig');
    }


    /**
    * @Route("/meus-documentos", name="dashboard_driver_documents")
    */
    public function driverDocumentsAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_PROFESSIONAL')) {
            $em = $this->getDoctrine()->getManager();

            $document = $em->getRepository("AppBundle:DocumentDriver")->findOneByDriver($this->getUser());

            return $this->render('AppBundle:Dashboard/Driver:index.html.twig',array('document' => $document));
        }
    }

    /**
     * Displays a form to create a new DocumentDriver entity.
     *
     * @Route("/meus-documentos/enviar", name="dashboard_driver_documents_new")
     * @Method("GET")
     */
    public function driverDocumentsNewAction()
    {
        
        if ($this->get('security.authorization_checker')->isGranted('ROLE_PROFESSIONAL')) {
            $em = $this->getDoctrine()->getManager();
            $document = $em->getRepository("AppBundle:DocumentDriver")->findOneBy(array('driver' => $this->getUser(), 'status' => 1));

            if(!$document){

                $entity = new DocumentDriver();
                $form   = $this->createCreateForm($entity);

                $documentDriver = $em->getRepository("AppBundle:DocumentDriver")->findOneByDriver($this->getUser());
                return $this->render('AppBundle:Dashboard/Driver:new.html.twig',array(
                    'entity' => $entity,
                    'form'   => $form->createView(),
                    'document' => $documentDriver
                ));
            }else{
                return $this->redirectToRoute('dashboard_driver_documents');
            }
        } else {
            throw $this->createNotFoundException('Você não tem permissão para acessar esta tela.');
        }
    }

    /**
     * Creates a new DocumentDriver entity.
     *
     * @Route("/meus-documentos/enviar", name="dashboard_driver_documents_create")
     * @Method("POST")
     */
    public function driverDocumentsCreateAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_PROFESSIONAL')) {

            $em = $this->getDoctrine()->getManager();

            $documentDriver = $em->getRepository("AppBundle:DocumentDriver")->findOneByDriver($this->getUser());
            if($documentDriver){
                $em->remove($documentDriver);
                $em->flush();
            }

            $entity = new DocumentDriver();
            $form = $this->createCreateForm($entity);
            $form->handleRequest($request);

            if ($form->isValid()) {
                    try {
                        $entity->setDriver($this->getUser());
                        $entity->setStatus(0);

                        $em->persist($entity);
                        $em->flush();

                        $this->sendEmail('Vanbora - Confirmação de envio de documento', $email, '
                            <div style="width: 100%; background: #baebf7; padding: 10px 0;display:block;float:none;margin: 0 auto;">
                            <img src="https://beta.vanbora.today/bundles/app/frontend/img/logo.png" alt="Vanbora" style="width:80px; height:80px;margin: -6px auto;margin-top: -6px;margin-right: auto;margin-bottom: -6px;margin-left: auto;display:block;"/>
                            </div>
                            <div style="width: 39%; background: #fff; padding: 0 60px;display:block;float:none;margin: 0 auto;margin-top:45px;">
                            <h1 style="color: #020d16; font-weight:500; font-size: 18px;margin: -30px -45px 0 0;text-align:center;font-weight:600;">Seu documento foi enviado com sucesso!</h1>
                            <p style="color: #666666; font-weight: 400; font-size:16px;margin: 20px 0 0 0;text-align:left;line-height:24px;">Olá, '.$entity->getFirstName().'.<br><br></p>
                            <p style="color: #666666; font-weight: 400; font-size:13px;margin:0;text-align:left;line-height:24px;">
                            Agora você só precisa aguardar a nossa análise e então já iniciar os seus anúncios.<br><br></p>
                            <p style="color: #666666; font-weight: 400; font-size:15px;margin: 20px 0;text-align:left;">Um abraço,<br><b>Equipe Vanbora.</b></p>
                            <p style="color: #666666; font-weight: 400; font-size:16px;margin: 20px 0;text-align:center;">Em caso de dúvida, entre em contato conosco.<br><a style="color: #666666;" href="mailto:contato@vanbora.today">contato@vanbora.today</a> </p>

                            </div>
                        ');

                        $request->getSession()
                         ->getFlashBag()
                         ->add('success', 'Registro criado com sucesso!');

                        return $this->redirect($this->generateUrl('dashboard_driver_documents', array('id' => $entity->getId())));
                    } catch (\Exception $e) {
                        $request->getSession()
                             ->getFlashBag()
                         ->add('error', 'Ocorreu algum erro inesperado. Tente novamente mais tarde!');
                    }
            }

            return $this->render('AppBundle:Dashboard/Driver:new.html.twig',array(
                'entity' => $entity,
                'form'   => $form->createView()
            ));
        } else {
            throw $this->createNotFoundException('Você não tem permissão para acessar esta tela.');
        }
    }

    /**
     * Creates a form to create a DocumentDriver entity.
     *
     * @param DocumentDriver $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(DocumentDriver $entity)
    {
        $form = $this->createForm(new DocumentDriverType(), $entity, array(
            'action' => $this->generateUrl('dashboard_driver_documents_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
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
