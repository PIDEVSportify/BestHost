<?php

namespace App\Repository;

use App\Entity\ConversationReply;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ConversationReply|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConversationReply|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConversationReply[]    findAll()
 * @method ConversationReply[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConversationReplyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConversationReply::class);
    }

    // /**
    //  * @return ConversationReply[] Returns an array of ConversationReply objects
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
    public function findOneBySomeField($value): ?ConversationReply
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
