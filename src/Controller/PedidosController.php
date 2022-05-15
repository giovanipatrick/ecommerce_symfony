<?php

namespace App\Controller;

use App\Entity\Pedidos;
use App\Repository\PedidosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Exception;
use stdClass;

class PedidosController extends AbstractController
{

    /** 
     * Trata os dados e gera uma exception caso os critérios não sejam atendidos
     */
    private function validateParams($request,$update)
    {
        if($update){
            if(!intval($request->get('id'))){
                throw new Exception('O identificar do pedido não foi informado!');
            }
        }

        if(!intval($request->get('forma_pagamento'))){
            throw new Exception('A forma de pagamento não foi informada!');
        }

        if(!intval($request->get('situacao'))){
            throw new Exception('A situação do pedido não foi informadoa!');
        }

        if(!floatval($request->get('valor'))){
            throw new Exception('O valor do pedido não foi informado!');
        }
    }

    /** 
     * Cria um objeto com os dados enviados no Body da requisição e o retorna
     */
    private function reqParams($request)
    {
        $data = new stdClass;
        $data->id = intval($request->get('id'));
        $data->forma_pagamento = intval($request->get('forma_pagamento'));
        $data->situacao = intval($request->get('situacao'));
        $data->valor = floatval($request->get('valor'));
        return $data;
    }

    /** 
     * @Route("/pedidos/create", name="app_pedidos_create", methods="POST")
     */
    public function create(PedidosRepository $pedidosRepository, Request $request) : Response
    {
        try{

            $this->validateParams($request,false);
            $values = $this->reqParams($request);

            $pedido = new Pedidos;
            $pedido->setFormaPagamento($values->forma_pagamento);
            $pedido->setSituacao($values->situacao);
            $pedido->setValor($values->valor);
            $pedido->setRemoved(0);
            $pedido->setCreatedAt(new \DateTime());

            $pedidosRepository->add($pedido,true);

            return $this->json(
                array("code"=>200,"type"=>"success","message"=>"O pedido foi registrado com sucesso!"),
                200
            );
            
        }catch(Exception $e){

            return $this->json(
                array("code"=>400,"type"=>"error","message"=>$e->getMessage()),
                400
            );
            
        }
    }

    /** 
     * @Route("/pedidos/update", name="app_pedidos_update", methods="PUT")
     */
    public function update(EntityManagerInterface $em, PedidosRepository $pedidosRepository, Request $request) : Response
    {
        try{

            $this->validateParams($request,false);
            $values = $this->reqParams($request);

            $pedido = $pedidosRepository->find($values->id);

            if(!$pedido){
                return $this->json(
                    array("code"=>400,"type"=>"error","message"=>"Pedido não encontrado!"),
                    400
                );
            }

            $pedido->setFormaPagamento($values->forma_pagamento);
            $pedido->setSituacao($values->situacao);
            $pedido->setValor($values->valor);
            $pedido->setUpdatedAt(new \DateTime());

            $em->flush();

            return $this->json(
                array("code"=>200,"type"=>"success","Pedido atualizado com sucesso!"),
                200
            );


        }catch(Exception $e){

            return $this->json(
                array("code"=>400,"type"=>"error","message"=>$e->getMessage()),
                400
            );

        }
    }

    /** 
     * @Route("/pedidos/inactivate/{id}", name="app_pedidos_inactivate", methods="DELETE")
     */
    public function inactivatePedido($id = null,EntityManagerInterface $em, PedidosRepository $pedidosRepository, Request $request) : Response
    {

        try{
            
            if(intval($id)){

                $pedido = $pedidosRepository->find($id);

                if(!$pedido){
                    return $this->json(
                        array("code"=>400,"type"=>"error","message"=>"O pedido não foi encontrado!")
                    );
                }

                $pedido->setRemoved(1);
                $pedido->setUpdatedAt(new \DateTime());

                $em->flush();

                return $this->json(
                    array("code"=>200,"type"=>"success","message"=>"O pedido foi removido com sucesso"),
                    200
                );

            }else{
                return $this->json(
                    array("code"=>400,"type"=>"error","message"=>"O identificar do pedido não foi informado!"),
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
     * @Route("/pedidos/informations/{id}", name="app_pedidos_informations", methods="GET")
     */
    public function getPedido($id = null, PedidosRepository $pedidosRepository) : Response
    {

        try{

            if(!intval($id)){
                return $this->json(
                    array("code"=>400,"type"=>"error","message"=>"O identificar do pedido não foi informado"),
                    400
                );
            }

            $pedido = $pedidosRepository->find($id);

            if(!$pedido){
                return $this->json(
                    array("code"=>400,"type"=>"error","message"=>"Pedido não encontrado!"),
                    400
                );
            }

            return $this->json(
                array("code"=>200,"type"=>"success","message"=>$pedido),
                200
            );

        }catch(Exception $e){

            return $this->json(
                array("code"=>500,"type"=>"error","message"=>$e->getMessage()),
                500
            );

        }

    }

    /** 
     * @Route("/pedidos/listPedidos", name="app_pedidos_list", methods="GET")
     */
    public function listAll(PedidosRepository $pedidosRepository) : Response
    {

        try{

            $pedido = $pedidosRepository->findAll();

            if(!$pedido){
                return $this->json(
                    array("code"=>400,"type"=>"error","message"=>"Nenhum pedido encontrado!"),
                    400
                );
            }

            return $this->json(
                array("code"=>200,"type"=>"success","message"=>$pedido),
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
