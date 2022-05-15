<?php

namespace App\Controller;

use App\Entity\Produtos;
use App\Repository\ProdutosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Exception;
use stdClass;

class ProdutosController extends AbstractController
{

    /** 
     * Trata os dados e gera uma exception caso os critérios não sejam atendidos
     */
    private function validateParams($request,$update)
    {
        if($update){
            if(!intval($request->get('id'))){
                throw new Exception('O identificar do produto não foi informado!');
            }
        }

        if(!$request->get('nome') && strlen($request->get('nome')) > 200){
            throw new Exception('O nome não pode ser vazio e deve possuir menos de 200 caracteres!');
        }

        if(!intval($request->get('categoria'))){
            throw new Exception('A categoria do produto não foi informada!');
        }

        if(!intval($request->get('codigo_barra'))){
            throw new Exception('O codigo de barras não foi informado!');
        }

        if(!floatval($request->get('valor'))){
            throw new Exception('O valor do produto não foi informado');
        }

        if(!intval($request->get('quantidade'))){
            throw new Exception('A quantidade do produto não foi informado!');
        }

        if(!$request->get('descricao')){
            throw new Exception('A descrição do produto não foi informada!');
        }

        if(!$request->get('peso')){
            throw new Exception('O peso do produto não foi informado!');
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
        $data->categoria = intval($request->get('categoria'));
        $data->codigo_barra = intval($request->get('codigo_barra'));
        $data->valor = floatval($request->get('valor'));
        $data->quantidade = intval($request->get('quantidae'));
        $data->descricao = filter_var($request->get('descricao'),FILTER_SANITIZE_ADD_SLASHES);
        $data->peso = filter_var($request->get('peso'),FILTER_SANITIZE_ADD_SLASHES);
        return $data;
    }

    /** 
     * @Route("/produto/create", name="app_produto_create", methods="POST")
     */
    public function create(ProdutosRepository $produtosRepository, Request $request) :Response 
    {
        try{
            
            $this->validateParams($request,false);
            
            $values = $this->reqParams($request);
            
            $produtosEntity = new Produtos;
            $produtosEntity->setNome($values->nome);
            $produtosEntity->setCodigoBarra($values->codigo_barra);
            $produtosEntity->setValor($values->valor);
            $produtosEntity->setQuantidade($values->quantidade);
            $produtosEntity->setDescricao($values->descricao);
            $produtosEntity->setPeso($values->peso);
            $produtosEntity->setRemoved(0);
            $produtosEntity->setCreatedAt(new \DateTime());

            $produto = $produtosRepository->findByNome($produtosEntity->getNome());

            if(!$produto){
                $produtosRepository->add($produtosEntity,true);
                return $this->json(
                    array("code"=>200,"type"=>"success","message"=>"Produto cadastrado com sucesso!"),
                    200
                );
            }else{
                return $this->json(
                    array("code"=>400,"type"=>"error","message"=>"O produto informado já está cadastrado!"),
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
     * @Route("/produto/update", name="app_produto_update", methods="PUT")
     */
    public function update(EntityManagerInterface $em, ProdutosRepository $produtosRepository, Request $request) : Response 
    {
        try{

            $this->validateParams($request,true);

            $values = $this->reqParams($request);

            $produto = $produtosRepository->find($values->id);

            if(!$produto){
                return $this->json(
                    array("code"=>"400","type"=>"error","message"=>"O produto é inválido!"),
                    400
                );
            }

            $produto->setNome($values->nome);
            $produto->setCodigoBarra($values->codigo_barra);
            $produto->setValor($values->valor);
            $produto->setQuantidade($values->quantidade);
            $produto->setDescricao($values->descricao);
            $produto->setPeso($values->peso);
            $produto->setUpdatedAt(new \DateTime());

            $em->flush();
            
            return $this->json(
                array("code"=>200,"type"=>"success","message"=>"O produto foi atualizado com sucesso!"),
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
     * @Route("/produto/inactivate/{id}", name="app_produto_inactivate", methods="DELETE")
     */
    public function inactivateProduct($id = null, EntityManagerInterface $em, ProdutosRepository $produtosRepository) : Response
    {
        try{

            if(intval($id)){

                $produto = $produtosRepository->find($id);

                if(!$produto){
                    return $this->json(
                        array("code"=>400,"type"=>"error","message"=>"O produto não foi encontrado!"),
                        400
                    );
                }

                $produto->setRemoved(1);
                $produto->setUpdatedAt(new \DateTime());

                $em->flush();

                return $this->json(
                    array("code"=>200,"type"=>"success","message"=>"O produto foi removido com sucesso!"),
                    200
                );

            }else{
                return $this->json(
                    array("code"=>400,"type"=>"error","message"=>"O identificar do produto não foi informado"),
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
     * @Route("/produto/informations/{id}", name="app_produto_informations", methods="GET")
     */
    public function getProductInformations($id = null,ProdutosRepository $produtosRepository) : Response 
    {
        try{

            if(intval($id)){

                $produto = $produtosRepository->find($id);

                if(!$produto){
                    return $this->json(
                        array("code"=>400,"type"=>"error","message"=>"O produto não foi encontrado!")
                    );
                }

                return $this->json(
                    array("code"=>200,"type"=>"success","message"=>$produto),
                    200
                );


            }else{
                return $this->json(
                    array("code"=>400,"type"=>"error","message"=>"O identificar do produto não foi informado"),
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
     * @Route("/produto/listProducts", name="app_produto_list", methods="GET")
     */
    public function listAll(ProdutosRepository $produtosRepository) : Response
    {
        try{

            $produto = $produtosRepository->findAll();

            if(!$produto){
                return $this->json(
                    array("code"=>400,"type"=>"error","message"=>"Nenhum produto encontrado!"),
                    400
                );
            }

            return $this->json(
                array("code"=>200,"type"=>"success","message"=>$produto),
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
