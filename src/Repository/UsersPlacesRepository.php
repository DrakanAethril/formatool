<?php

namespace App\Repository;

use App\Entity\UsersPlaces;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UsersPlaces>
 *
 * @method UsersPlaces|null find($id, $lockMode = null, $lockVersion = null)
 * @method UsersPlaces|null findOneBy(array $criteria, array $orderBy = null)
 * @method UsersPlaces[]    findAll()
 * @method UsersPlaces[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsersPlacesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UsersPlaces::class);
    }

    //    /**
    //     * @return UsersPlaces[] Returns an array of UsersPlaces objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?UsersPlaces
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
