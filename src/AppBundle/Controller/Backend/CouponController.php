<?php

namespace AppBundle\Controller\Backend;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Coupon;
use AppBundle\Form\CouponType;

/**
 * Coupon controller.
 *
 * @Route("/backend/coupon")
 */
class CouponController extends Controller
{
    /**
     * Lists all Coupon entities.
     *
     * @Route("/", name="backend_coupon")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Coupon')->findAll();

        return $this->render('AppBundle:Backend\Coupon:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new Coupon entity.
     *
     * @Route("/new", name="backend_coupon_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $coupon = new Coupon();
        $form = $this->createForm('AppBundle\Form\CouponType', $coupon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if ($coupon->getTypeValue() == 'Monetário') {
                $value = (str_replace(".", "", $coupon->getMonetaryValue()));
                $value = (str_replace(",", ".", $value));
                if ($coupon->getMonetaryValue() != null) {
                    $coupon->setMonetaryValue($value);
                    $coupon->setPercentValue(null);
                }
            } else {
                $value = (str_replace("%", "", $coupon->getPercentValue()));
                $coupon->setPercentValue($value);
                $coupon->setMonetaryValue(null);
            }

            $em->persist($coupon);
            $em->flush();

            $request->getSession()
                 ->getFlashBag()
                 ->add('success', 'Registro criado com sucesso!');

            return $this->redirectToRoute('backend_coupon');
        }

        return $this->render('AppBundle:Backend\Coupon:new.html.twig', array(
            'entity' => $coupon,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Coupon entity.
     *
     * @Route("/{id}/edit", name="backend_coupon_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Coupon $coupon)
    {
        $deleteForm = $this->createDeleteForm($coupon);
        if ($coupon->getMonetaryValue() != null) {
            $value = number_format($coupon->getMonetaryValue(), 2, ',', '.');
            $coupon->setMonetaryValue($value);
        }
        $editForm = $this->createForm('AppBundle\Form\CouponType', $coupon);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if ($coupon->getTypeValue() == 'Monetário') {
                $value = (str_replace(".", "", $coupon->getMonetaryValue()));
                $value = (str_replace(",", ".", $value));
                if ($coupon->getMonetaryValue() != null) {
                    $coupon->setMonetaryValue($value);
                    $coupon->setPercentValue(null);
                }
            } else {
                $value = (str_replace("%", "", $coupon->getPercentValue()));
                $coupon->setPercentValue($value);
                $coupon->setMonetaryValue(null);
            }
            $em->persist($coupon);
            $em->flush();


            $request->getSession()
                 ->getFlashBag()
                 ->add('success', 'Registro atualizado com sucesso!');

            return $this->redirectToRoute('backend_coupon', array('id' => $coupon->getId()));
        }

        return $this->render('AppBundle:Backend\Coupon:edit.html.twig', array(
            'entity' => $coupon,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Coupon entity.
     *
     * @Route("/{id}/delete", name="backend_coupon_delete")
     */
    public function deleteAction(Request $request, Coupon $coupon)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($coupon);
        $em->flush();

        $request->getSession()
             ->getFlashBag()
             ->add('success', 'Registro excluído com sucesso!');

        return $this->redirectToRoute('backend_coupon');
    }

    /**
     * Creates a form to delete a Coupon entity.
     *
     * @param Coupon $coupon The Coupon entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Coupon $coupon)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('backend_coupon_delete', array('id' => $coupon->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
