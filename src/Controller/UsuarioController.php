<?php 

namespace App\Controller;

use App\Entity\Usuarios;
use App\Repository\UsuariosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Exception;
use stdClass;

class UsuarioController extends AbstractController{

    /** 
     * Trata os dados e gera uma Exception caso os critérios não sejam atendidos
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
     * @Route("/usuario/authenticate", name="app_usuario_authenticate", methods="POST")
     */
    public function authUser(UsuariosRepository $userRepo, Request $request) : Response
    {
        try{
            if($request->get('email')){
                if($request->get('password')){
                    $usuario = $userRepo->findByEmailAndPassword($request->get('email'),md5($request->get('password')));
                        if(!$usuario){
                            return $this->json(
                                array("code"=>400,"type"=>"error","message"=>"Usuário ou senha incorreto(a)s"),
                                400
                            );
                        }else{
                            return $this->json(
                                array("code"=>200,"type"=>"success","message"=>"Usuário autenticado!"),
                                200
                            );
                        }
                }else{
                    return $this->json(
                        array("code"=>400,"type"=>"error","message"=>"A senha não foi informada"),
                        400
                    );
                }
            }else{
                return $this->json(
                    array("code"=>400,"type"=>"error","message"=>"O email não foi informado"),
                    400
                );
            }
        }catch(Exception $e){
            return $this->json(
                array("code"=>500,"type"=>"error","message"=>$e->getMessage()),
                500
            );
        }
    }

    /**
     * @Route("/usuario/create", name="app_usuario_create", methods="POST")
     */
    public function createUser(UsuariosRepository $userRepo,Request $request) : Response
    {
        try{

            $this->validateCreateAndUpdateParams($request,false);
            $values = $this->reqParams($request);
            
            $userEnt = new Usuarios;
            $userEnt->setNome($values->nome);
            $userEnt->setSobrenome($values->sobrenome);
            $userEnt->setEmail($values->email);
            $userEnt->setPassword($values->password);
            $userEnt->setGrupo($values->grupo);
            $userEnt->setRemoved(0);
            $userEnt->setCreatedAt(new \DateTime());

            if(!$userRepo->findByEmail($userEnt->getEmail())){
                $userRepo->add($userEnt,true);
                return $this->json(
                    array("code"=>200,"type"=>"success","message"=>"Usuário cadastrado com sucesso!"),
                    200
                );
            }else{
                return $this->json(
                    array("code"=>400,"type"=>"error","message"=>"Usuário já cadastrado!"),
                    400
                );
            }

        }catch(Exception $e){
            return $this->json(
                array("code"=>400,"type"=>"error","message"=>$e->getMessage()),
                500
            );
        }
    }

    /** 
     * @Route("/usuario/update", name="app_usuario_update", methods="PUT")
     */
    public function updateUser(EntityManagerInterface $em, UsuariosRepository $userRepo, Request $request) : Response
    {
        try{

            $this->validateCreateAndUpdateParams($request,true);
            $values = $this->reqParams($request);

            $usuario = $userRepo->find($values->id);

            if(!$usuario){
                return $this->json(
                    array("code"=>400,"type"=>"error","message"=>"O usuário não foi encontrado!"),
                    400
                );
            }

            $usuario->setNome($values->nome);
            $usuario->setSobrenome($values->sobrenome);
            $usuario->setEmail($values->email);
            $usuario->setGrupo($values->grupo);
            $usuario->setUpdatedAt(new \DateTime());

            $em->flush();

            return $this->json(
                array("code"=>200,"type"=>"success","message"=>"O usuário foi atualizado com sucesso!"),
                200
            );

        }catch(Exception $e){
            return $this->json(
                array("code"=>400,"type"=>"error","message"=>$e->getMessage()),
                500
            );
        }
    }

    /** 
     * @Route("/usuario/inactivate/{id}", name="app_usuario_inactivate", methods="DELETE")
     */
    public function inactivateUser($id = null, EntityManagerInterface $em, UsuariosRepository $userRepo, Request $request) : Response
    {
        try{

            if(intval($id)){

                $usuario = $userRepo->find($id);
    
                if(!$usuario){
                    return $this->json(
                        array("code"=>400,"type"=>"error","message"=>"O usuário não foi encontrado!"),
                        400
                    );
                }
    
                $usuario->setRemoved(1);
                $usuario->setUpdatedAt(new \DateTime());
    
                $em->flush();
    
                return $this->json(
                    array("code"=>200,"type"=>"success","message"=>"O usuario foi removido com sucesso!"),
                    200
                );
    
            }else{
                return $this->json(
                    array("code"=>400,"type"=>"error","message"=>"O identificar do usuário não foi informado"),
                400);
            }

        }catch(Exception $e){
            return $this->json(
                array("code"=>500,"type"=>"error","message"=>$e->getMessage()),
                500
            );
        }
    }

    /** 
     * @Route("/usuario/informations/{id}", name="app_usuario_informations", methods="GET")
     */
    public function getUserInformations($id = null, UsuariosRepository $userRepo, Request $request) : Response
    {
        try{

            if(intval($id)){

                $usuario = $userRepo->find($id);
    
                if(!$usuario){
                    return $this->json(
                        array("code"=>400,"type"=>"error","message"=>"Usuário não encontrado!"),
                        400
                    );
                }
    
                return $this->json(
                    array("code"=>200,"type","success","message"=>$usuario),
                    200
                );
    
            }else{
                return $this->json(
                    array("code"=>400,"type"=>"error","message"=>"O identificar do usuário não foi informado"),
                    400
                );
            }

        }catch(Exception $e){
            return $this->json(
                array("code"=>500,"type"=>"error","message"=>$e->getMessage()),
                500
            );
        }
    }

    /** 
     * @Route("/usuario/listUsers", name="app_usuario_all", methods="GET")
     */
    public function getAllUsers(UsuariosRepository $userRepo, Request $request) : Response
    {
        try{

            $usuario = $userRepo->findAll();

            if(!$usuario){
                return $this->json(
                    array("code"=>204,"type"=>"success","message"=>"Nenhum usuário encontrado!"),
                    204
                );
            }

            return $this->json(
                array("code"=>200,"type"=>"success","message"=>$usuario),
                200
            );

        }catch(Exception $e){
            return $this->json(
                array("code"=>500,"type"=>"error","message"=>$e->getMessage()),
                500
            );
        }
    }
}