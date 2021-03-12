<?php

namespace App\Repository;

use App\Entity\ActLike;
use App\Entity\Activity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ActLike|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActLike|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActLike[]    findAll()
 * @method ActLike[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActLikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActLike::class);
    }

    // /**
    //  * @return ActLike[] Returns an array of ActLike objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ActLike
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
