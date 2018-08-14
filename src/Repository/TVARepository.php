<?php

namespace App\Repository;

use App\Entity\TVA;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TVA|null find($id, $lockMode = null, $lockVersion = null)
 * @method TVA|null findOneBy(array $criteria, array $orderBy = null)
 * @method TVA[]    findAll()
 * @method TVA[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TVARepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TVA::class);
    }

//    /**
//     * @return TVA[] Returns an array of TVA objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TVA
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
