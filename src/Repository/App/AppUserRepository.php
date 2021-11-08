<?php

namespace App\Repository\App;

use App\Entity\App\AppUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method AppUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method AppUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method AppUser[]    findAll()
 * @method AppUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method null loadUserByIdentifier(string $identifier)
 */
class AppUserRepository extends ServiceEntityRepository implements UserLoaderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AppUser::class);
    }

    public function loadUserByUsername($email): ?AppUser
    {
        return $this->createQueryBuilder('appPerson')
            ->select('appPerson')
            ->where('appPerson.email = :query')
            ->setParameter('query', $email)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function __call($name, $arguments)
    {
        // TODO: Implement @method null loadUserByIdentifier(string $identifier)
    }
}
