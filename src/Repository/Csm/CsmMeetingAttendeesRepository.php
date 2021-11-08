<?php

namespace App\Repository\Csm;

use App\Entity\Csm\CsmMeetingAttendees;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CsmMeetingAttendees|null find($id, $lockMode = null, $lockVersion = null)
 * @method CsmMeetingAttendees|null findOneBy(array $criteria, array $orderBy = null)
 * @method CsmMeetingAttendees[]    findAll()
 * @method CsmMeetingAttendees[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CsmMeetingAttendeesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CsmMeetingAttendees::class);
    }

    // /**
    //  * @return CsmMeetingAttendees[] Returns an array of CsmMeetingAttendees objects
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
    public function findOneBySomeField($value): ?CsmMeetingAttendees
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
