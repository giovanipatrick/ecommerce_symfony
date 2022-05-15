<?php

namespace App\Repository;

use App\Entity\Produtos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produtos>
 *
 * @method Produtos|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produtos|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produtos[]    findAll()
 * @method Produtos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProdutosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produtos::class);
    }

    public function add(Produtos $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Produtos $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByNome($nome): ?Produtos
    {
       return $this->createQueryBuilder('p')
            ->andWhere('p.nome = :nome')
            ->setParameter('nome', $nome)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
