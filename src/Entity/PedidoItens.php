<?php

namespace App\Entity;

use App\Repository\PedidoItensRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PedidoItensRepository::class)]
class PedidoItens
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Produtos::class, inversedBy: 'pedidoItens')]
    #[ORM\JoinColumn(nullable: false)]
    private $produto;

    #[ORM\ManyToOne(targetEntity: Pedidos::class, inversedBy: 'pedidoItens')]
    #[ORM\JoinColumn(nullable: false)]
    private $pedido;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduto(): ?Produtos
    {
        return $this->produto;
    }

    public function setProduto(?Produtos $produto): self
    {
        $this->produto = $produto;

        return $this;
    }

    public function getPedido(): ?Pedidos
    {
        return $this->pedido;
    }

    public function setPedido(?Pedidos $pedido): self
    {
        $this->pedido = $pedido;

        return $this;
    }
}
