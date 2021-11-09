<?php

namespace App\Repository\Csm;

use App\Entity\Csm\CsmProjectRequirement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CsmProjectRequirement|null find($id, $lockMode = null, $lockVersion = null)
 * @method CsmProjectRequirement|null findOneBy(array $criteria, array $orderBy = null)
 * @method CsmProjectRequirement[]    findAll()
 * @method CsmProjectRequirement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CsmProjectRequirementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CsmProjectRequirement::class);
    }

    // /**
    //  * @return CsmProjectRequirement[] Returns an array of CsmProjectRequirement objects
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
    public function findOneBySomeField($value): ?CsmProjectRequirement
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
