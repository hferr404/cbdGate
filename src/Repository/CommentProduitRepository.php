<?php

namespace App\Repository;

use App\Entity\CommentProduit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommentProduit|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommentProduit|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommentProduit[]    findAll()
 * @method CommentProduit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommentProduit::class);
    }

    // /**
    //  * @return CommentProduit[] Returns an array of CommentProduit objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CommentProduit
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
