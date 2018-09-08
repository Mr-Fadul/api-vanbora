<?php

namespace AppBundle\Controller\Backend;

use AppBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Category;
use AppBundle\Form\CategoryType;

/**
 * Category controller.
 *
 * @Route("/backend/category")
 */
class CategoryController extends BaseController
{

    /**
     * @Route("/" , name="backend_category")
     * @Template("AppBundle:Backend\Category:index.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Category')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Category entity.
     *
     * @Route("/create", name="backend_category_create")
     * @Method("POST")
     * @Template("AppBundle:Backend\Category:new.html.twig")
     */
    public function createAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
            $entity = new Category();
            $form = $this->createCreateForm($entity);
            $form->handleRequest($request);
            if ($form->isValid()) {
                try {
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($entity);
                    $em->flush();

                    $request->getSession()
                    ->getFlashBag()
                    ->add('success', 'Registro criado com sucesso!');

                    return $this->redirect($this->generateUrl('backend_category', array('id' => $entity->getId())));
                } catch (\Exception $e) {
                    $this->log($e);
                    $request->getSession()
                    ->getFlashBag()
                    ->add('error', 'Ocorreu algum erro inesperado. Tente novamente mais tarde!');
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
     * Creates a form to create a Category entity.
     *
     * @param Category $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Category $entity)
    {
        $form = $this->createForm(new CategoryType(), $entity, array(
            'action' => $this->generateUrl('backend_category_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Category entity.
     *
     * @Route("/new", name="backend_category_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
            $entity = new Category();
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
     * Displays a form to edit an existing Category entity.
     *
     * @Route("/{id}/edit", name="backend_category_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('AppBundle:Category')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Category entity.');
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
    * Creates a form to edit a Category entity.
    *
    * @param Category $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Category $entity)
    {
        $form = $this->createForm(new CategoryType(), $entity, array(
            'action' => $this->generateUrl('backend_category_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Category entity.
     *
     * @Route("/{id}", name="backend_category_update")
     * @Method("PUT")
     * @Template("AppBundle:Backend\Category:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
            $em = $this->getDoctrine()->getManager();

            $category = $em->getRepository('AppBundle:Category')->find($id);

            if (!$category) {
                throw $this->createNotFoundException('Unable to find Category entity.');
            }

            $editForm = $this->createEditForm($category);
            $editForm->handleRequest($request);

            if ($editForm->isValid()) {
                try {
                    $em->flush();

                    $request->getSession()
                        ->getFlashBag()
                        ->add('success', 'Registro atualizado com sucesso!');

                    return $this->redirect($this->generateUrl('backend_category', array('id' => $id)));
                } catch (\Exception $e) {
                    $this->log($e);
                    $request->getSession()
                    ->getFlashBag()
                    ->add('error', 'Ocorreu algum erro inesperado. Tente novamente mais tarde!');
                }
            }

            return array(
                'entity'      => $category,
                'edit_form'   => $editForm->createView(),
            );
        } else {
            throw $this->createNotFoundException('Você não tem permissão para acessar esta tela.');
        }
    }
    
    /**
     * Deletes a Category entity.
     *
     * @Route("/{id}/delete", name="backend_category_delete")
     *
     */
    public function deleteAction(Request $request, $id)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Category')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Category entity.');
            }

            $em->remove($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('backend_category'));
        } else {
            throw $this->createNotFoundException('Você não tem permissão para acessar esta tela.');
        }
    }

    public function slugify($string)
    {
        $string = preg_replace('/[\t\n]/', ' ', $string);
        $string = preg_replace('/\s{2,}/', ' ', $string);
        $list = array(
            'Š' => 'S',
            'š' => 's',
            'Đ' => 'Dj',
            'đ' => 'dj',
            'Ž' => 'Z',
            'ž' => 'z',
            'Č' => 'C',
            'č' => 'c',
            'Ć' => 'C',
            'ć' => 'c',
            'À' => 'A',
            'Á' => 'A',
            'Â' => 'A',
            'Ã' => 'A',
            'Ä' => 'A',
            'Å' => 'A',
            'Æ' => 'A',
            'Ç' => 'C',
            'È' => 'E',
            'É' => 'E',
            'Ê' => 'E',
            'Ë' => 'E',
            'Ì' => 'I',
            'Í' => 'I',
            'Î' => 'I',
            'Ï' => 'I',
            'Ñ' => 'N',
            'Ò' => 'O',
            'Ó' => 'O',
            'Ô' => 'O',
            'Õ' => 'O',
            'Ö' => 'O',
            'Ø' => 'O',
            'Ù' => 'U',
            'Ú' => 'U',
            'Û' => 'U',
            'Ü' => 'U',
            'Ý' => 'Y',
            'Þ' => 'B',
            'ß' => 'Ss',
            'à' => 'a',
            'á' => 'a',
            'â' => 'a',
            'ã' => 'a',
            'ä' => 'a',
            'å' => 'a',
            'æ' => 'a',
            'ç' => 'c',
            'è' => 'e',
            'é' => 'e',
            'ê' => 'e',
            'ë' => 'e',
            'ì' => 'i',
            'í' => 'i',
            'î' => 'i',
            'ï' => 'i',
            'ð' => 'o',
            'ñ' => 'n',
            'ò' => 'o',
            'ó' => 'o',
            'ô' => 'o',
            'õ' => 'o',
            'ö' => 'o',
            'ø' => 'o',
            'ù' => 'u',
            'ú' => 'u',
            'û' => 'u',
            'ý' => 'y',
            'ý' => 'y',
            'þ' => 'b',
            'ÿ' => 'y',
            'Ŕ' => 'R',
            'ŕ' => 'r',
            '/' => '-',
            ' ' => '-',
            '.' => '-',
        );

        $string = strtr($string, $list);
        $string = preg_replace('/-{2,}/', '-', $string);
        $string = strtolower($string);

        return $string;
    }
}
