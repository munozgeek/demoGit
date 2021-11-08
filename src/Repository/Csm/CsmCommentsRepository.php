<?php

namespace App\Repository\Csm;

use App\Entity\Csm\CsmComments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CsmComments|null find($id, $lockMode = null, $lockVersion = null)
 * @method CsmComments|null findOneBy(array $criteria, array $orderBy = null)
 * @method CsmComments[]    findAll()
 * @method CsmComments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CsmCommentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CsmComments::class);
    }

    // /**
    //  * @return CsmComments[] Returns an array of CsmComments objects
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
    public function findOneBySomeField($value): ?CsmComments
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
