<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\AdType;
use AppBundle\Entity\Ad;

/**
 * Ad controller
 *
 * @Route("/anuncio")
 */
class AdController extends Controller{

    /**
     * Displays a form to create a new Ad entity.
     *
     * @Route("/criar", name="ad_new")
     * @Method("GET")
     */
    public function newAction()
    {
        
        if ($this->get('security.authorization_checker')->isGranted('ROLE_PROFESSIONAL')) {
            $em = $this->getDoctrine()->getManager();
            $document = $em->getRepository("AppBundle:DocumentDriver")->findOneBy(array('driver' => $this->getUser(), 'status' => 1));

            if($document){
                $entity = new Ad();
                $form   = $this->createCreateForm($entity);

                return $this->render('AppBundle:Dashboard/Ad:new.html.twig',array(
                    'entity' => $entity,
                    'form'   => $form->createView()
                ));
            }else{
                return $this->redirectToRoute('dashboard_ads');
            }
        } else {
            throw $this->createNotFoundException('Você não tem permissão para acessar esta tela.');
        }
    }

    /**
     * Creates a new Ad entity.
     *
     * @Route("/criar", name="ad_create")
     * @Method("POST")
     */
    public function createAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_PROFESSIONAL')) {

            $em = $this->getDoctrine()->getManager();

            $entity = new Ad();
            $form = $this->createCreateForm($entity);
            $form->handleRequest($request);

            if ($form->isValid()) {
                    try {
                        $entity->setDriver($this->getUser());
                        $entity->setActive(0);
                        $em->persist($entity);
                        $em->flush();

                        $this->sendEmail('Vanbora - Confirmação de envio de anúncio', $this->getUser()->getEmail(), '
                            <div style="width: 100%; background: #baebf7; padding: 10px 0;display:block;float:none;margin: 0 auto;">
                            <img src="https://beta.vanbora.today/bundles/app/frontend/img/logo.png" alt="Vanbora" style="width:80px; height:80px;margin: -6px auto;margin-top: -6px;margin-right: auto;margin-bottom: -6px;margin-left: auto;display:block;"/>
                            </div>
                            <div style="width: 39%; background: #fff; padding: 0 60px;display:block;float:none;margin: 0 auto;margin-top:45px;">
                            <h1 style="color: #020d16; font-weight:500; font-size: 18px;margin: -30px -45px 0 0;text-align:center;font-weight:600;">Seu anúncio foi enviado com sucesso!</h1>
                            <p style="color: #666666; font-weight: 400; font-size:16px;margin: 20px 0 0 0;text-align:left;line-height:24px;">Olá, '.$this->getUser()->getFirstName().'.<br><br></p>
                            <p style="color: #666666; font-weight: 400; font-size:13px;margin:0;text-align:left;line-height:24px;">
                            Agora você só precisa aguardar a nossa análise e então seu anúncio será publicado automaticamente.<br><br></p>
                            <p style="color: #666666; font-weight: 400; font-size:15px;margin: 20px 0;text-align:left;">Um abraço,<br><b>Equipe Vanbora.</b></p>
                            <p style="color: #666666; font-weight: 400; font-size:16px;margin: 20px 0;text-align:center;">Em caso de dúvida, entre em contato conosco.<br><a style="color: #666666;" href="mailto:contato@vanbora.today">contato@vanbora.today</a> </p>

                            </div>
                        ');

                        return $this->redirect($this->generateUrl('dashboard_ads', array('id' => $entity->getId())));
                    } catch (\Exception $e) {
                        
                    }
            }

            return $this->render('AppBundle:Dashboard/Ad:new.html.twig',array(
                'entity' => $entity,
                'form'   => $form->createView()
            ));
        } else {
            throw $this->createNotFoundException('Você não tem permissão para acessar esta tela.');
        }
    }
	
    /**
     * @Route("/{slug}", name="ad_show")
     */
    public function indexAction(Request $request,$slug){
    	$em = $this->getDoctrine()->getManager();

      $ad = $em->getRepository("AppBundle:Ad")->findOneBySlug($slug);

      if(!$ad){
        throw $this->createNotFoundException('Anúncio não existe, ou tá desativado.');
      }

      if (!$ad->getActive() and !$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
        throw $this->createNotFoundException('Anúncio desativado.'); 
      }

      $locales = $em->getRepository("AppBundle:WhereISpend")->findByWhere($ad);

      return $this->render('AppBundle:Ad:index.html.twig',array('ad' => $ad, 'locales' => $locales));
    }

    /**
     * Creates a form to create a Ad entity.
     *
     * @param Ad $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Ad $entity)
    {
        $form = $this->createForm(new AdType(), $entity, array(
            'action' => $this->generateUrl('ad_create'),
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