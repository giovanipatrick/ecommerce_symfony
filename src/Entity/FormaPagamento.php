<?php

namespace App\Entity;

use App\Repository\FormaPagamentoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormaPagamentoRepository::class)]
class FormaPagamento
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 200)]
    private $nome;

    #[ORM\Column(type: 'datetime')]
    private $created_at;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $updated_at;

    #[ORM\OneToOne(mappedBy: 'forma_pagamento', targetEntity: Pedidos::class, cascade: ['persist', 'remove'])]
    private $pedidos;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(string $nome): self
    {
        $this->nome = $nome;

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

    public function getPedidos(): ?Pedidos
    {
        return $this->pedidos;
    }

    public function setPedidos(?Pedidos $pedidos): self
    {
        // unset the owning side of the relation if necessary
        if ($pedidos === null && $this->pedidos !== null) {
            $this->pedidos->setFormaPagamento(null);
        }

        // set the owning side of the relation if necessary
        if ($pedidos !== null && $pedidos->getFormaPagamento() !== $this) {
            $pedidos->setFormaPagamento($this);
        }

        $this->pedidos = $pedidos;

        return $this;
    }
}
