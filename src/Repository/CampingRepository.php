<?php

namespace App\Repository;

use App\Entity\Camping;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method Camping|null find($id, $lockMode = null, $lockVersion = null)
 * @method Camping|null findOneBy(array $criteria, array $orderBy = null)
 * @method Camping[]    findAll()
 * @method Camping[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CampingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Camping::class);
    }

    /**
      * @return Camping[] Returns an array of Camping objects
      */
    public function sql(Request $request):array
    {
        $conn = $this->getEntityManager();
        $dql=$conn->createQuery('SELECT c FROM App\Entity\Camping c,App\Entity\Offre o  WHERE o.prix_offre >= :min and o.prix_offre <= :max and c.offre_id=o.id');

        $dql->setParameter('min',$request->request->get('min_price'));
        $dql->setParameter('max',$request->request->get('max_price'));
        return $dql->getResult();
    }
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
    public function findOneBySomeField($value): ?Camping
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
