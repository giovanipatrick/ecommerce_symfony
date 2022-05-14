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

    #[ORM\Column(type: 'integer')]
    private $produto;

    #[ORM\Column(type: 'integer')]
    private $pedido;

    #[ORM\Column(type: 'datetime')]
    private $created_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduto(): ?int
    {
        return $this->produto;
    }

    public function setProduto(int $produto): self
    {
        $this->produto = $produto;

        return $this;
    }

    public function getPedido(): ?int
    {
        return $this->pedido;
    }

    public function setPedido(int $pedido): self
    {
        $this->pedido = $pedido;

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
