<?php

namespace App\Entity;

use App\Repository\PedidosItensRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PedidosItensRepository::class)]
class PedidosItens
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToMany(targetEntity: Pedidos::class, inversedBy: 'pedidosItens')]
    private $pedido;

    #[ORM\ManyToMany(targetEntity: Produtos::class, inversedBy: 'pedidosItens')]
    private $produto;

    #[ORM\Column(type: 'datetime')]
    private $created_at;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $updated_at;

    public function __construct()
    {
        $this->pedido = new ArrayCollection();
        $this->produto = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Pedidos>
     */
    public function getPedido(): Collection
    {
        return $this->pedido;
    }

    public function addPedido(Pedidos $pedido): self
    {
        if (!$this->pedido->contains($pedido)) {
            $this->pedido[] = $pedido;
        }

        return $this;
    }

    public function removePedido(Pedidos $pedido): self
    {
        $this->pedido->removeElement($pedido);

        return $this;
    }

    /**
     * @return Collection<int, Produtos>
     */
    public function getProduto(): Collection
    {
        return $this->produto;
    }

    public function addProduto(Produtos $produto): self
    {
        if (!$this->produto->contains($produto)) {
            $this->produto[] = $produto;
        }

        return $this;
    }

    public function removeProduto(Produtos $produto): self
    {
        $this->produto->removeElement($produto);

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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}
