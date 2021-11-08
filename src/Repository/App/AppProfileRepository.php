<?php

namespace App\Repository\App;

use App\Entity\App\AppProfile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AppProfile|null find($id, $lockMode = null, $lockVersion = null)
 * @method AppProfile|null findOneBy(array $criteria, array $orderBy = null)
 * @method AppProfile[]    findAll()
 * @method AppProfile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppProfileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AppProfile::class);
    }

    // /**
    //  * @return AppProfile[] Returns an array of AppProfile objects
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

    /**
     * @param $code
     * @return AppProfile|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function code($code): ?AppProfile
    {
        return $this->createQueryBuilder('appProfile')
            ->andWhere('appProfile.code = :code')
            ->setParameter('code', $code)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
