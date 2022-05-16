<?php

namespace App\Repository;

use App\Entity\Pedidos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pedidos>
 *
 * @method Pedidos|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pedidos|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pedidos[]    findAll()
 * @method Pedidos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PedidosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pedidos::class);
    }

    public function add(Pedidos $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Pedidos $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   public function findById($id)
   {
       $conn = $this->getEntityManager()->getConnection();

       $sql = 'SELECT 
       p.*, 
       f.nome AS forma_pagamento, 
       s.nome AS situacao
     FROM 
       pedidos AS p 
       INNER JOIN forma_pagamento AS f ON(p.forma_pagamento_id = f.id) 
       INNER JOIN situacao AS s ON(p.situacao_id = s.id) 
       INNER JOIN usuarios AS u ON(p.usuario_id = u.id)
     WHERE 
       p.id = :id';

       $stmt = $conn->prepare($sql);

       $resul = $stmt->executeQuery(array(
            ":id"=>$id 
       ));

       return $resul->fetchAllAssociative();

   }

   public function findAllWithJoin()
   {
       $conn = $this->getEntityManager()->getConnection();

       $sql = "SELECT 
       p.*, 
       f.nome AS forma_pagamento, 
       s.nome AS situacao 
     FROM 
       pedidos AS p 
       INNER JOIN forma_pagamento AS f ON(p.forma_pagamento_id = f.id) 
       INNER JOIN situacao AS s ON(p.situacao_id = s.id)
       INNER JOIN usuarios AS u ON(p.usuario_id = u.id)";

       $stmt = $conn->prepare($sql);

       $resul = $stmt->executeQuery();

       return $resul->fetchAllAssociative();
   }

   public function getAttrProducts($pedido_id)
   {
       $conn = $this->getEntityManager()->getConnection();

       $sql = "SELECT 
       p.nome, 
       p.codigo_barra, 
       p.descricao, 
       p.peso 
     FROM 
       pedido_itens AS i 
       INNER JOIN produtos AS p ON(i.produto_id = p.id) 
       INNER JOIN pedidos AS ped ON(i.pedido_id = ped.id) 
     WHERE 
       i.pedido_id = :pedido_id";

       $stmt = $conn->prepare($sql);

       $resul = $stmt->executeQuery(array(
            ":pedido_id"=>$pedido_id
       ));

       return $resul->fetchAllAssociative();
   }
}
