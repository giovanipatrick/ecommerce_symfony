<?php

namespace App\Entity;

use App\Repository\FotoProdutoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FotoProdutoRepository::class)]
class FotoProduto
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Produtos::class, inversedBy: 'fotoProdutos')]
    private $produto;

    #[ORM\Column(type: 'blob')]
    private $foto;

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

    public function getFoto()
    {
        return $this->foto;
    }

    public function setFoto($foto): self
    {
        $this->foto = $foto;

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
