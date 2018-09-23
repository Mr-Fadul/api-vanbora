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

}
