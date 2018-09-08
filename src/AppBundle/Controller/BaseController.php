<?php

namespace AppBundle\Controller;

use JMS\Serializer\SerializerBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Inscription;
use AppBundle\Entity\PaymentStatus;

class BaseController extends Controller{
    
    public function __call($method, $arguments){
        if (preg_match('/^get(\w+)Repository$/', $method, $matches)) {
            return $this->getDoctrine()->getRepository('AppBundle:' . $matches[1]);
        } else {
            throw new \BadMethodCallException("Undefined method '$method'. Provide a valid repository name!");
        }
    }

    public function returnJson($return, $status = 200){
        $serializer = SerializerBuilder::create()->build();
        $return = $serializer->serialize($return, 'json');
        return new Response($return, $status, array('Content-Type' => 'application/json'));
    }

    protected function getLogger(){
        return $this->get("logger");
    }

    public function getUser(){
        return parent::getUser();
    }

    protected function getUserRepository(){
        return $this->getDoctrine()->getRepository('UserBundle:User');
    }

    
    protected function getRepositoryFromBase($entityName, $bundle = 'AppBundle'){
        return $this->getDoctrine()->getRepository($bundle.':'.ucfirst($entityName));
    }

    public function verifyAdmin(){
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
            throw $this->createNotFoundException('Você não tem permissão para acessar esta tela.');
        }
    }

    public function validUsernameEmail($entity){
        $repository = $this->getUserRepository();
        $flag = $repository->getUserByNameAndUsername($entity);

        return $flag;
    }

    protected function log($message, $level = "error"){
        if (is_array($message)) {
            $message = print_r($message, true);
        }

        $this->getLogger()->log($level, '[vanbora] ' . $message);
    }

    public function getDataForLong(){
        setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Sao_Paulo');
        return strftime('%d de %B de %Y', strtotime('today'));
    }

    public function successMessage($request, $msg = ''){
        $msg = !empty($msg) ? $msg : 'Registro atualizado com sucesso!';

        $request->getSession()
                ->getFlashBag()
                ->add('success', $msg);
    }

    public function successDataUpdateMessage($request){
        $request->getSession()
                ->getFlashBag()
                ->add('success', 'Dados atualizados com sucesso!');
    }

    public function warningMessage(Request $request, $msg = ''){
        $msg = !empty($msg) ? $msg : 'Atencão!';

        $request->getSession()
                ->getFlashBag()
                ->add('warning', $msg);
    }

    public function errorMessage($request, $msg = ''){
        if (empty($msg)){
            $msg = 'Ocorreu algum erro inesperado. Tente novamente mais tarde!';
        }

        $messageFlash = $request->getSession()->getFlashBag();
        $messageFlash->add('error', $msg);
    }

    public function removeCharacters($field){
        return preg_replace('/[^A-Za-z0-9\']/', '', $field);
    }

    public function responseDataCustom($dataResponse){
        $response = new Response(json_encode($dataResponse));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function sendEmailPaymentFail($user){
        $clientService = $this->container->get('client.service');

        $config = array(
            'subject' => 'Vanbora - Ops...algo aconteceu',
            'from' => 'contato@vanbora.today',
            'to' => $user->getEmail(),
        );

        $config['message'] = '
        <div style="width: 100%; background: #020d16; padding: 10px 0;display:block;float:none;margin: 0 auto;">
            <img src="https://vanbora.today/bundles/app/frontend/img/logo.png" alt="Vanbora" style="margin: 0 auto;display:block;"/>
            </div>
            <div style="width: 70%; background: #fff; padding: 0 60px;display:block;float:none;margin: 0 auto;margin-top:45px;">
            <h1 style="color: #020d16; font-weight:500; font-size: 18px;margin: -30px -45px 0 0;text-align:center;font-weight:600;">Olá ' . $user->getFirstName() . ',</h1>
            <p style="color: #666666; font-weight: 400; font-size:13px;margin:0;text-align:left;line-height:24px;">
                Vimos que você fez um pedido no Vanbora, mas algo aconteceu. Por algum motivo seu pedido não foi liberado pela operadora do seu cartão.
            </p>
            <p style="color: #666666; font-weight: 700; font-size:13px;margin:0;text-align:left;line-height:24px;">
                Sugerimos que você entre em contato com sua operadora de cartão para entender o ocorrido.
            </p>
            <p style="color: #666666; font-weight: 700; font-size:13px;margin:0;text-align:left;line-height:24px;">
                Estamos ansiosos para que o seu anúncio seja impulsionado.
            </p>
            <p style="color: #666666; font-weight: 400; font-size:15px;margin: 20px 0;text-align:left;">Abraços</p>
            <p style="color: #666666; font-weight: 400; font-size:15px;margin: 20px 0;text-align:left;">Vanbora</p>
            <p style="color: #666666; font-weight: 400; font-size:16px;margin: 20px 0;text-align:center;">Em caso de dúvida, entre em contato conosco.<br><a style="color: #666666;" href="mailto:contato@vanbora.today">contato@vanbora.today</a> </p>

            </div>
        ';

        $clientService->sendEmailCustom($config);
    }
}