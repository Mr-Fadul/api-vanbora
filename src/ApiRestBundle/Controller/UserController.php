<?php

namespace ApiRestBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as FOS;
use UserBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;

class UserController extends Controller
{

    /**
     * @FOS\Get("/list", name="api_rest_user", options={"method_prefix" = false, "expose"=true })
     * @FOS\View(statusCode=200, serializerEnableMaxDepthChecks=true, serializerGroups={"listagemUsuario"})
     */
    public function searchAllUsersAction(Request $request)
    {
        $service = $this->get('api.service.usuario');
        $user = $service->searchAllUser();

        $serializer = SerializerBuilder::create()->build();
        $return = $serializer->serialize($user, 'json');
        return new Response($return, 200, array('Content-Type' => 'application/json'));
    }

    /**
     * @FOS\Get("/busca/{id}", name="api_rest_usuario_busca_id", options={"method_prefix" = false, "expose"=true })
     * @FOS\View(statusCode=200, serializerEnableMaxDepthChecks=true, serializerGroups={"listagemUsuario"})
     */
    public function buscaUsuarioIdAction(Request $request, $id)
    {
        $servico = $this->get('api.service.usuario');
        $usuario = $servico->buscaUsuarioId($id);

        return array(
            'usuario' => $usuario,
        );
    }

    /**
     * @FOS\Get("/busca-perfil/{perfil}", name="api_rest_usuario_listagem_perfil", options={"method_prefix" = false, "expose"=true })
     * @FOS\View(statusCode=200, serializerEnableMaxDepthChecks=true, serializerGroups={"Default", "listagemUsuario"})
     */
    public function buscaUsuarioPerfilIdAction(Request $request, $perfil)
    {
        $servico = $this->get('api.service.usuario');

        $usuarios = $servico->buscaUsuariosPerfilId($perfil);

        return array(
            'usuarios' => $usuarios,
        );
    }

    /**
     * @FOS\Get("/form/{id}", name="api_rest_usuario_form", options={"method_prefix" = false, "expose"=true })
     * @FOS\View(statusCode=200, serializerEnableMaxDepthChecks=true, serializerGroups={"formUsuario"})
     */
    public function buscaUsuarioFormAction(Request $request, $id)
    {
        $servico = $this->get('api.service.usuario');
        $usuario = $servico->buscaUsuarioId($id);

        return array(
            'usuario' => $usuario,
        );
    }

    /**
     * @FOS\Post("/cadastrar", name="api_rest_usuario_cadastrar", options={"method_prefix" = false, "expose"=true })
     * @FOS\View(statusCode=201, serializerEnableMaxDepthChecks=true, serializerGroups={"listagemUsuario"})
     * @param $request
     * @return mixed
     */
    public function cadastrarUsuarioAction(Request $request)
    {
        $servico = $this->get('api.service.usuario');
        $usuario = $servico->cadastrarUsuarioAdmin($request);

        return array(
            'usuario' => $usuario,
        );
    }


    /**
     * @FOS\Post("/cadastro-aluno", name="api_rest_usuario_cadastrar_aluno", options={"method_prefix" = false, "expose"=true })
     * @FOS\View(statusCode=201, serializerEnableMaxDepthChecks=true, serializerGroups={"listagemUsuario"})
     * @param $request
     * @return mixed
     */
    public function cadastrarAlunoAction(Request $request)
    {
        $servico = $this->get('api.service.usuario');
        $usuario = $servico->cadastrarAluno($request);

        return array(
            'usuario' => $usuario,
        );
    }

    /**
     * @FOS\Post("/cadastro-professor", name="api_rest_usuario_cadastrar_professor", options={"method_prefix" = false, "expose"=true })
     * @FOS\View(statusCode=201, serializerEnableMaxDepthChecks=true, serializerGroups={"listagemUsuario"})
     * @param $request
     * @return mixed
     */
    public function cadastrarProfessorAction(Request $request)
    {
        $servico = $this->get('api.service.usuario');
        $usuario = $servico->cadastrarProfessor($request);

        return array(
            'usuario' => $usuario,
        );
    }

    /**
     * @FOS\Put("/editar", name="api_rest_usuario_editar", options={"method_prefix" = false, "expose"=true })
     * @FOS\View(statusCode=201, serializerEnableMaxDepthChecks=true, serializerGroups={"listagemUsuario"})
     * @param $request
     * @return mixed
     */
    public function editarUsuarioAction(Request $request)
    {
        $servico = $this->get('api.service.usuario');
        $usuario = $servico->editarUsuario($request);
        
        return array(
            'usuario' => $usuario,
        );
    }

    /**
     * @FOS\Post("/editar-aluno", name="api_rest_usuario_editar_aluno", options={"method_prefix" = false, "expose"=true })
     * @FOS\View(statusCode=201, serializerEnableMaxDepthChecks=true, serializerGroups={"listagemUsuario"})
     * @param $request
     * @return mixed
     */
    public function editarUsuarioAlunoAction(Request $request)
    {
        $servico = $this->get('api.service.usuario');
        $usuario = $servico->editarUsuarioAluno($request);
        
        return array(
            'usuario' => $usuario,
        );
    }

    /**
     * @FOS\Delete("/deletar/{id}", name="api_rest_usuario_deletar_aluno", options={"method_prefix" = false, "expose"=true })
     * @FOS\View(statusCode=201, serializerEnableMaxDepthChecks=true, serializerGroups={"listagemAula"})
     * @param $request
     * @return mixed
     */
    public function deletarAlunoAction(Request $request, $id)
    {
        $servico = $this->get('api.service.usuario');
        $usuario = $servico->deletar($id);

        return array(
            'aula' => $usuario,
        );
    }

    /**
     * @FOS\Get("/autocomplete/{string}", name="api_rest_usuario_autocomplete_aluno", options={"method_prefix" = false, "expose"=true })
     * @FOS\View(statusCode=200, serializerEnableMaxDepthChecks=true, serializerGroups={"autocomplete"})
     */
    public function autoCompleteAction(Request $request, $string)
    {

        $servico = $this->get('api.service.usuario');
        $alunos = $servico->getAlunosAutocomplete($string);

        return array(
            'alunos' => $alunos,
        );
    }

    /**
     * @FOS\Get("/relatorio-assinantes", name="api_rest_usuario_relatorio_assinantes", options={"method_prefix" = false, "expose"=true })
     * @FOS\View(statusCode=200, serializerEnableMaxDepthChecks=true, serializerGroups={"relatorioAssinatura"})
     */
    public function assinantesRelatorioAction(Request $request)
    {

        $servico = $this->get('api.service.usuario');
        $assinantes = $servico->buscaAssinantes($request);

        return array(
            'assinantes' => $assinantes,
        );
    }
}
