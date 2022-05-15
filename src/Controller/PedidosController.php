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
    public function create() : Response
    {
        try{
            
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    /** 
     * @Route("/pedidos/update", name="app_pedidos_update", methods="PUT")
     */
    public function update() : Response
    {

    }

    /** 
     * @Route("/pedidos/inactivate/{id}", name="app_pedidos_inactivate", methods="DELETE")
     */
    public function inactivatePedido() : Response
    {

    }

    /** 
     * @Route("/pedidos/informations/{id}", name="app_pedidos_informations", methods="GET")
     */
    public function getPedido() : Response
    {

    }

    /** 
     * @Route("/pedidos/listPedidos", name="app_pedidos_list", methods="GET")
     */
    public function listAll() : Response
    {

    }
    
}
