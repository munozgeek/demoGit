<?php

namespace App\Repository\Csm;

use App\Entity\Csm\CsmCompany;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CsmCompany|null find($id, $lockMode = null, $lockVersion = null)
 * @method CsmCompany|null findOneBy(array $criteria, array $orderBy = null)
 * @method CsmCompany[]    findAll()
 * @method CsmCompany[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CsmCompanyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CsmCompany::class);
    }

    // /**
    //  * @return CsmCompany[] Returns an array of CsmCompany objects
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
    public function findOneBySomeField($value): ?CsmCompany
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
