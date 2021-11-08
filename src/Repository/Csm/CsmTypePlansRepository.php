<?php

namespace App\Repository\Csm;

use App\Entity\Csm\CsmTypePlans;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CsmTypePlans|null find($id, $lockMode = null, $lockVersion = null)
 * @method CsmTypePlans|null findOneBy(array $criteria, array $orderBy = null)
 * @method CsmTypePlans[]    findAll()
 * @method CsmTypePlans[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CsmTypePlansRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CsmTypePlans::class);
    }

    // /**
    //  * @return CsmTypePlans[] Returns an array of CsmTypePlans objects
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
    public function findOneBySomeField($value): ?CsmTypePlans
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
