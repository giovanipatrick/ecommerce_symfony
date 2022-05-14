<?php

namespace App\Entity;

use App\Repository\ProdutosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProdutosRepository::class)]
class Produtos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 200)]
    private $nome;

    #[ORM\Column(type: 'integer')]
    private $categoria;

    #[ORM\Column(type: 'integer')]
    private $codigo_barra;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 3)]
    private $valor;

    #[ORM\Column(type: 'integer')]
    private $quantidade;

    #[ORM\Column(type: 'text')]
    private $descricao;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 3)]
    private $peso;

    #[ORM\Column(type: 'integer')]
    private $removed;

    #[ORM\Column(type: 'datetime')]
    private $created_at;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $updated_at;

    #[ORM\ManyToMany(targetEntity: PedidosItens::class, mappedBy: 'produto')]
    private $pedidosItens;

    #[ORM\OneToMany(mappedBy: 'produto', targetEntity: FotoProduto::class)]
    private $fotoProdutos;

    public function __construct()
    {
        $this->pedidosItens = new ArrayCollection();
        $this->fotoProdutos = new ArrayCollection();
    }

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

    public function getCategoria(): ?int
    {
        return $this->categoria;
    }

    public function setCategoria(int $categoria): self
    {
        $this->categoria = $categoria;

        return $this;
    }

    public function getCodigoBarra(): ?int
    {
        return $this->codigo_barra;
    }

    public function setCodigoBarra(int $codigo_barra): self
    {
        $this->codigo_barra = $codigo_barra;

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

    public function getQuantidade(): ?int
    {
        return $this->quantidade;
    }

    public function setQuantidade(int $quantidade): self
    {
        $this->quantidade = $quantidade;

        return $this;
    }

    public function getDescricao(): ?string
    {
        return $this->descricao;
    }

    public function setDescricao(string $descricao): self
    {
        $this->descricao = $descricao;

        return $this;
    }

    public function getPeso(): ?string
    {
        return $this->peso;
    }

    public function setPeso(string $peso): self
    {
        $this->peso = $peso;

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
            $pedidosIten->addProduto($this);
        }

        return $this;
    }

    public function removePedidosIten(PedidosItens $pedidosIten): self
    {
        if ($this->pedidosItens->removeElement($pedidosIten)) {
            $pedidosIten->removeProduto($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, FotoProduto>
     */
    public function getFotoProdutos(): Collection
    {
        return $this->fotoProdutos;
    }

    public function addFotoProduto(FotoProduto $fotoProduto): self
    {
        if (!$this->fotoProdutos->contains($fotoProduto)) {
            $this->fotoProdutos[] = $fotoProduto;
            $fotoProduto->setProduto($this);
        }

        return $this;
    }

    public function removeFotoProduto(FotoProduto $fotoProduto): self
    {
        if ($this->fotoProdutos->removeElement($fotoProduto)) {
            // set the owning side to null (unless already changed)
            if ($fotoProduto->getProduto() === $this) {
                $fotoProduto->setProduto(null);
            }
        }

        return $this;
    }

}
