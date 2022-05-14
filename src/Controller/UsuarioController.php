<?php 

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Exception;
use stdClass;


/** 
 * Classe responsável pela manipulação dos usuários
 * @author Giovani Patrick <giovani@diminua.me>
 * @package Ecommerce
 * @version 1.0
 */
class UsuarioController extends AbstractController{

    /** 
     * Trata os dados e gera uma Exception caso os critérios não sejam atendidos
     * @param Request $request
     * @param boolean $update True para dados específicos a rota Update
     * @throws Exception
     * @return void
     */
    private function validateCreateAndUpdateParams($request,$update)
    {
        if($update){
            if(!$request->get('id')){
                throw new Exception('O identificar do usuário não foi informado!');
            }
        }

        if(!$request->get('nome') || strlen($request->get('nome')) > 200){
            throw new Exception('O nome não pode estar em branco e deve possuir no máximo 200 caracteres!');
        }

        if(!$request->get('sobrenome') || strlen($request->get('sobrenome')) > 200){
            throw new Exception('O sobrenome não pode estar em branco e deve possuir no máximo 200 caracteres!');
        }

        if(!$request->get('email') || strlen($request->get('email')) > 200){
            throw new Exception('O email não pode estar em branco e deve possuir no máximo 200 caracteres!');
        }

        if(!$update){
            if(!$request->get('password') || strlen($request->get('password')) < 8 || strlen($request->get('password')) > 30){
                throw new Exception('A senha deve possuir no minimo 8 caracteres e no máximo 30');
            }
        }

        if(!intval($request->get('grupo'))){
            throw new Exception('O grupo de permissões não foi informado!');
        }

        return;
    }

    /** 
     * Cria um objeto com os dados enviados no Body da requisição e o retorna
     * @param Request $request 
     * @return stdClass
     */
    private function reqParams($request)
    {
        $data = new stdClass;
        $data->id = intval($request->get('id'));
        $data->nome = filter_var($request->get('nome'),FILTER_SANITIZE_ADD_SLASHES);
        $data->sobrenome = filter_var($request->get('sobrenome',FILTER_SANITIZE_ADD_SLASHES));
        $data->email = filter_var($request->get('email'),FILTER_SANITIZE_EMAIL);
        $data->password = filter_var(md5($request->get('password')),FILTER_SANITIZE_ADD_SLASHES);
        $data->grupo = filter_var($request->get('grupo'),FILTER_SANITIZE_ADD_SLASHES);
        return $data;
    }

    /**
     * Autenticação do Usuário
     * @Route("/usuario/authenticate", name="app_usuario_authenticate", methods="POST")
     * @param Request $request
     * @throws Exception
     * @return string JSON
     */
    public function authUser(Request $request) : Response
    {
        try{
            if($request->get('email')){
                if($request->get('password')){
                    return $this->json(
                        array("code"=>200,"type"=>"success","message"=>"Usuário autenticado"),
                    200);
                }else{
                    throw new Exception('A senha não foi informada!');
                }
            }else{
                throw new Exception('O email não foi informado!');
            }
        }catch(Exception $e){
            return $this->json(array("code"=>400,"type"=>"error","message"=>$e->getMessage()));
        }
    }

    /**
     * Criar Usuário
     * @Route("/usuario/create", name="app_usuario_create", methods="POST")
     * @param Request $request
     * @return string JSON
     */
    public function createUser(Request $request) : Response
    {
        try{
            $this->validateCreateAndUpdateParams($request,false);
            $values = $this->reqParams($request);
            return $this->json(array("code"=>200,"type"=>"success","message"=>$values));
        }catch(Exception $e){
            return $this->json(array("code"=>400,"type"=>"error","message"=>$e->getMessage()));
        }
    }

    /** 
     * Atualizar Usuário
     * @Route("/usuario/update", name="app_usuario_update", methods="PUT")
     * @param Request $request
     * @return string JSON
     */
    public function updateUser(Request $request) : Response
    {
        try{
            $this->validateCreateAndUpdateParams($request,true);
            $values = $this->reqParams($request);
            return $this->json(array("code"=>200,"type"=>"success","message"=>$values));
        }catch(Exception $e){
            return $this->json(array("code"=>400,"type"=>"error","message"=>$e->getMessage()));
        }
    }

    /** 
     * Desabilitar Usuário
     * @Route("/usuario/inactivate/{id}", name="app_usuario_inactivate", methods="DELETE")
     * @param Request $request
     * @return string JSON
     */
    public function inactivateUser($id = null, Request $request) : Response
    {
        if(intval($id)){

        }else{
            return $this->json(
                array("code"=>400,"type"=>"error","message"=>"O identificar do usuário não foi informado"),
            400);
        }
    }

    /** 
     * Listar informações do Usuário
     * @Route("/usuario/informations/{id}", name="app_usuario_informations", methods="GET")
     * @param Request $request
     * @return string JSON
     */
    public function getUserInformations($id = null, Request $request) : Response
    {
        if(intval($id)){

        }else{
            return $this->json(
                array("code"=>400,"type"=>"error","message"=>"O identificar do usuário não foi informado"),
            400);
        }
    }

    /** 
     * Listar todos os usuários
     * @Route("/usuario/listUsers", name="app_usuario_all", methods="GET")
     * @param Request $request
     * @return string JSON
     */
    public function getAllUsers(Request $request) : Response
    {
        return new Response('test');
    }
}