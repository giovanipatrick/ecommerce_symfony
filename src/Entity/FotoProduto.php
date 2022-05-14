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

    #[ORM\Column(type: 'integer')]
    private $produto;

    #[ORM\Column(type: 'text')]
    private $foto;

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

    public function getFoto(): ?string
    {
        return $this->foto;
    }

    public function setFoto(string $foto): self
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
