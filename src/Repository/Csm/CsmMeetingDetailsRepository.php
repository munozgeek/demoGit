<?php

namespace App\Repository\Csm;

use App\Entity\Csm\CsmMeetingDetails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CsmMeetingDetails|null find($id, $lockMode = null, $lockVersion = null)
 * @method CsmMeetingDetails|null findOneBy(array $criteria, array $orderBy = null)
 * @method CsmMeetingDetails[]    findAll()
 * @method CsmMeetingDetails[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CsmMeetingDetailsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CsmMeetingDetails::class);
    }

    // /**
    //  * @return CsmMeetingDetails[] Returns an array of CsmMeetingDetails objects
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
    public function findOneBySomeField($value): ?CsmMeetingDetails
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
