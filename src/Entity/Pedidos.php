<?php

namespace App\Entity;

use App\Repository\PedidosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PedidosRepository::class)]
class Pedidos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: FormaPagamento::class, inversedBy: 'pedidos')]
    #[ORM\JoinColumn(nullable: false)]
    private $forma_pagamento;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 3)]
    private $valor;

    #[ORM\ManyToOne(targetEntity: Situacao::class, inversedBy: 'pedidos')]
    #[ORM\JoinColumn(nullable: false)]
    private $situacao;

    #[ORM\Column(type: 'integer')]
    private $removed;

    #[ORM\Column(type: 'datetime')]
    private $created_at;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $updated_at;

    #[ORM\ManyToMany(targetEntity: PedidosItens::class, mappedBy: 'pedido')]
    private $pedidosItens;

    public function __construct()
    {
        $this->pedidosItens = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFormaPagamento(): ?FormaPagamento
    {
        return $this->forma_pagamento;
    }

    public function setFormaPagamento(?FormaPagamento $forma_pagamento): self
    {
        $this->forma_pagamento = $forma_pagamento;

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

    public function getSituacao(): ?Situacao
    {
        return $this->situacao;
    }

    public function setSituacao(?Situacao $situacao): self
    {
        $this->situacao = $situacao;

        return $this;
    }

    public function getRemoved(): ?int
    {
        return $this->removed;
    }

    public function setRemoved(int $removed): self
    {
        $this->removed = $removed;

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

    /**
     * @return Collection<int, PedidosItens>
     */
    public function getPedidosItens(): Collection
    {
        return $this->pedidosItens;
    }

    public function addPedidosIten(PedidosItens $pedidosIten): self
    {
        if (!$this->pedidosItens->contains($pedidosIten)) {
            $this->pedidosItens[] = $pedidosIten;
            $pedidosIten->addPedido($this);
        }

        return $this;
    }

    public function removePedidosIten(PedidosItens $pedidosIten): self
    {
        if ($this->pedidosItens->removeElement($pedidosIten)) {
            $pedidosIten->removePedido($this);
        }

        return $this;
    }
}
