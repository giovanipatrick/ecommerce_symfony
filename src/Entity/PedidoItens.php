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

    #[ORM\Column(type: 'decimal', precision: 10, scale: 3)]
    private $valor;

    #[ORM\Column(type: 'datetime')]
    private $created_at;

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

    public function getValor(): ?string
    {
        return $this->valor;
    }

    public function setValor(string $valor): self
    {
        $this->valor = $valor;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }
}
