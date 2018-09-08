<?php

namespace AppBundle\Controller\Backend;

use AppBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Form\ClientType;
use AppBundle\Entity\Client;

/**
 * Client controller
 *
 * @Route("/backend/client")
 */
class ClientController extends BaseController{

    /**
     * Lists all Clients entities.
     *
     * @Route("/", name="backend_client")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
            $em = $this->getDoctrine()->getManager();

            $entities = $em->getRepository('AppBundle:Client')->findAll();

            return array(
              'entities' => $entities,
          );
        } else {
            throw $this->createNotFoundException('Você não tem permissão para acessar esta tela.');
        }
    }
    /**
     * Creates a new Client entity.
     *
     * @Route("/", name="backend_client_create")
     * @Method("POST")
     * @Template("AppBundle:Backend\Client:new.html.twig")
     */
    public function createAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
            $entity = new Client();
            $form = $this->createCreateForm($entity);
            $form->handleRequest($request);

            if ($form->isValid()) {
                if ($this->validUsernameEmail($entity)) {
                    try {
                        $em = $this->getDoctrine()->getManager();

                        $email = $form->get('email')->getData();

                        // Set entity fields custom
                        $this->requestCustomForm($request, $entity);

                        $entity->setUsername($email);
                        $entity->setEnabled(true);
                        $entity->setRoles(array("ROLE_USER"));

                        $em->persist($entity);
                        $em->flush();

                        $request->getSession()
                         ->getFlashBag()
                         ->add('success', 'Registro criado com sucesso!');

                        return $this->redirect($this->generateUrl('backend_client', array('id' => $entity->getId())));
                    } catch (\Exception $e) {
                        $request->getSession()
                ->getFlashBag()
                ->add('error', 'Ocorreu algum erro inesperado. Tente novamente mais tarde!');
                    }
                } else {
                    $request->getSession()
                    ->getFlashBag()
                    ->add('error', 'Email já cadastrado, tente novamente!');
                }
            }

            return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
        } else {
            throw $this->createNotFoundException('Você não tem permissão para acessar esta tela.');
        }
    }

    /**
     * Creates a form to create a Client entity.
     *
     * @param Client $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Client $entity)
    {
        $form = $this->createForm(new ClientType(), $entity, array(
            'action' => $this->generateUrl('backend_client_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Client entity.
     *
     * @Route("/new", name="backend_client_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
            $entity = new Client();
            $form   = $this->createCreateForm($entity);

            return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
        } else {
            throw $this->createNotFoundException('Você não tem permissão para acessar esta tela.');
        }
    }

    /**
     * Displays a form to edit an existing Client entity.
     *
     * @Route("/{id}/edit", name="backend_client_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('AppBundle:Client')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Client entity.');
            }

            $editForm = $this->createEditForm($entity);
            return array(
              'entity'      => $entity,
              'edit_form'   => $editForm->createView(),
            );
        } else {
            throw $this->createNotFoundException('Você não tem permissão para acessar esta tela.');
        }
    }

    /**
    * Creates a form to edit a Client entity.
    *
    * @param Client $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Client $entity)
    {
        $form = $this->createForm(new ClientType(), $entity, array(
            'action' => $this->generateUrl('backend_client_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Client entity.
     *
     * @Route("/{id}", name="backend_client_update")
     * @Method("PUT")
     * @Template("AppBundle:Client:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('AppBundle:Client')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find App entity.');
            }

            $editForm = $this->createEditForm($entity);
            $editForm->handleRequest($request);

            if ($editForm->isValid()) {
                if ($this->validUsernameEmail($entity)) {
                    try {
                        // Set entity fields custom
                        $this->requestCustomForm($request, $entity);

                        $em->flush();

                        return $this->redirect($this->generateUrl('backend_client', array('id' => $id)));
                    } catch (\Exception $e) {
                        $request->getSession()
                ->getFlashBag()
                ->add('error', 'Ocorreu algum erro inesperado. Tente novamente mais tarde!');
                    }
                } else {
                    $request->getSession()
                    ->getFlashBag()
                    ->add('error', 'Email já cadastrado, tente novamente!');
                }
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
     * Deletes a Client entity.
     *
     * @Route("/{id}/delete", name="backend_client_delete")
     *
     */
    public function deleteAction(Request $request, $id)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Client')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find client entity.');
            }

            $em->remove($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('backend_client'));
        } else {
            throw $this->createNotFoundException('Você não tem permissão para acessar esta tela.');
        }
    }

    /**
    * Atualiza o Status do Usuários para ATIVO
    *
    * @Route("/?id={id}", name="backend_client_status")
    */
    public function activateStatusAction($id)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('AppBundle:Client')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Client entity.');
            }

            $condicao = $entity->getStatus();
            if ($condicao == "Habilitado") {
                $entity->setEnabled(false);
            } else {
                $entity->setEnabled(true);
            }

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('backend_client', array('id' => $id)));
        } else {
            throw $this->createNotFoundException('Você não tem permissão para acessar esta tela.');
        }
    }

    /**
     * Set entity fields custom
     * @param Request $request
     * @param Entity $entity
     */
    public function requestCustomForm($request, $entity)
    {
        $em = $this->getDoctrine()->getManager();
        $userCustomArray = $request->request->get('user_custom');

        if (!empty($userCustomArray['plainPassword'])) {
            $encoder = $this->container->get('security.encoder_factory')->getEncoder($entity);
            $new_pwd_encoded = $encoder->encodePassword($userCustomArray['plainPassword'], $entity->getSalt());
            $entity->setPassword($new_pwd_encoded);
        }
    }

    /**
     * Valida se o username e email são válidos
     * @param Client $entity
     * @return boolean $flag
     */
    public function validUsernameEmail($entity)
    {
        $em = $this->getDoctrine()->getManager();

        $flag = $em->getRepository('AppBundle:Client')->getUserByNameAndUsername($entity);

        return $flag;
    }

}