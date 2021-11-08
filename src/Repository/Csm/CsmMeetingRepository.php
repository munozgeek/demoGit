<?php

namespace App\Repository\Csm;

use App\Entity\Csm\CsmMeeting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CsmMeeting|null find($id, $lockMode = null, $lockVersion = null)
 * @method CsmMeeting|null findOneBy(array $criteria, array $orderBy = null)
 * @method CsmMeeting[]    findAll()
 * @method CsmMeeting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CsmMeetingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CsmMeeting::class);
    }

    // /**
    //  * @return CsmMeeting[] Returns an array of CsmMeeting objects
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
    public function findOneBySomeField($value): ?CsmMeeting
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
