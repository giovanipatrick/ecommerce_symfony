<?php

namespace App\Controller;

use App\Entity\PedidoItens;
use App\Entity\Pedidos;
use App\Repository\PedidosRepository;
use App\Repository\FormaPagamentoRepository;
use App\Repository\PedidoItensRepository;
use App\Repository\ProdutosRepository;
use App\Repository\SituacaoRepository;
use App\Repository\UsuariosRepository;
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

        if(!intval($request->get('usuario'))){
            throw new Exception('O usuário não foi informado!');
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
        $data->usuario = intval($request->get('usuario'));
        $data->forma_pagamento = intval($request->get('forma_pagamento'));
        $data->situacao = intval($request->get('situacao'));
        $data->valor = floatval($request->get('valor'));
        return $data;
    }

    /** 
     * @Route("/pedidos/create", name="app_pedidos_create", methods="POST")
     */
    public function create(PedidosRepository $pedidosRepository, UsuariosRepository $usuariosRepository, FormaPagamentoRepository $formaPagamentoRepository, SituacaoRepository $situacaoRepository, Request $request) : Response
    {
        try{

            $this->validateParams($request,false);
            $values = $this->reqParams($request);

            $forma_pagamento = $formaPagamentoRepository->find($values->forma_pagamento);
            $situacao = $situacaoRepository->find($values->situacao);

            if($forma_pagamento){
                if($situacao){

                    $usuario = $usuariosRepository->find($request->get('usuario'));

                    if(!$usuario){
                        return $this->json(
                            array("code"=>200,"type"=>"success","message"=>"O usuário não foi informado!"),
                            400
                        );
                    }

                    $pedido = new Pedidos;
                    $pedido->setFormaPagamento($forma_pagamento);
                    $pedido->setSituacao($situacao);
                    $pedido->setValor($values->valor);
                    $pedido->setRemoved(0);
                    $pedido->setCreatedAt(new \DateTime());
        
                    $pedidosRepository->add($pedido,true);
        
                    return $this->json(
                        array("code"=>200,"type"=>"success","message"=>"O pedido foi registrado com sucesso!"),
                        200
                    );

                }else{

                }
            }else{
                return $this->json(
                    array("code"=>400,"type"=>"error","message"=>"A forma de pagamento é invalida!"),
                    400
                );
            }
            
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
    public function update(EntityManagerInterface $em, UsuariosRepository $usuariosRepository, FormaPagamentoRepository $formaPagamentoRepository, SituacaoRepository $situacaoRepository, PedidosRepository $pedidosRepository, Request $request) : Response
    {
        try{

            $this->validateParams($request,false);
            $values = $this->reqParams($request);
            
            $forma_pagamento = $formaPagamentoRepository->find($values->forma_pagamento);

            if(!$forma_pagamento){
                return $this->json(
                    array("code"=>400,"type"=>"error","message"=>"A forma de pagamento é inválida!"),
                    400
                );
            }

            $usuario = $usuariosRepository->find($request->get('usuario'));

            if(!$usuario){
                return $this->json(
                    array("code"=>400,"type"=>"error","message"=>"O usuário não foi informado!"),
                    400
                );
            }

            $situacao = $situacaoRepository->find($values->situacao);

            if(!$situacao){
                return $this->json(
                    array("code"=>400,"type"=>"error","message"=>"A situação é inválida!"),
                    400
                );
            }

            $pedido = $pedidosRepository->find($values->id);

            if(!$pedido){
                return $this->json(
                    array("code"=>400,"type"=>"error","message"=>"Pedido não encontrado!"),
                    400
                );
            }

            $pedido->setFormaPagamento($forma_pagamento);
            $pedido->setSituacao($situacao);
            $pedido->setValor($values->valor);
            $pedido->setUpdatedAt(new \DateTime());

            $em->flush();

            return $this->json(
                array("code"=>200,"type"=>"success","message"=>"Pedido atualizado com sucesso!"),
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
    public function inactivatePedido($id = null,EntityManagerInterface $em, PedidosRepository $pedidosRepository) : Response
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

            $pedido = $pedidosRepository->findById($id);

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

            $pedido = $pedidosRepository->findAllWithJoin();

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

    /** 
     * @Route("/pedidos/attr_product", name="app_attr_products_create", methods="POST")
     */
    public function attrProduct(ProdutosRepository $produtosRepository, PedidoItensRepository $pedidoItensRepository, PedidosRepository $pedidosRepository, Request $request) : Response
    {
        try{

            $pedido_id = intval($request->get('pedido_id'));
            $produto_id = intval($request->get('produto_id'));
            $valor = floatval($request->get('valor'));
    
            if(!$pedido_id){
                return $this->json(
                    array("code"=>400,"type"=>"error","message"=>"O id do pedido não foi informado"),
                    400
                );
            }
    
            if(!$produto_id){
                return $this->json(
                    array("code"=>400,"type"=>"error","message"=>"O id do produto não foi informado!"),
                    400
                );
            }
    
            if(!$valor){
                return $this->json(
                    array("code"=>400,"type"=>"error","message"=>"O valor do produto não foi informado!"),
                    400
                );
            }
    
            $pedidoItens = new PedidoItens;
    
            $pedido = $pedidosRepository->find($pedido_id);
    
            if(!$pedido){
                return $this->json(
                    array("code"=>400,"type"=>"error","message"=>"O pedido não foi encontrado!"),
                    400
                );
            }
    
            $produto = $produtosRepository->find($produto_id);
    
            if(!$produto){
                return $this->json(
                    array("code"=>400,"type"=>"error","message"=>"O produto não foi encontrado!"),
                    400
                );
            }
    
            $pedidoItens->setPedido($pedido);
            $pedidoItens->setProduto($produto);
            $pedidoItens->setValor($valor);
            $pedidoItens->setCreatedAt(new \DateTime());
    
            $pedidoItensRepository->add($pedidoItens,true);
    
            return $this->json(
                array("code"=>200,"type"=>"success","message"=>"Produto atrelado ao pedido"),
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
     * @Route("/pedidos/attr_products/{pedido_id}", name="app_attr_products_list", methods="GET")
     */
    public function listAttrProducts($pedido_id = null,PedidosRepository $pedidosRepository) : Response
    {
        try{

            if(!intval($pedido_id)){
                return $this->json(
                    array("code"=>400,"type"=>"error","message"=>"O id do pedido não foi informado!"),
                    400
                );
            }

            $pedido = $pedidosRepository->getAttrProducts($pedido_id);

            if(!$pedido){
                return $this->json(
                    array("code"=>400,"type"=>"error","message"=>"Nenhum produto atrelado ao pedido informado!"),
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
