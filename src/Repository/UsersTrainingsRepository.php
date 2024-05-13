<?php

namespace App\Repository;

use App\Entity\UsersTrainings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UsersTrainings>
 *
 * @method UsersTrainings|null find($id, $lockMode = null, $lockVersion = null)
 * @method UsersTrainings|null findOneBy(array $criteria, array $orderBy = null)
 * @method UsersTrainings[]    findAll()
 * @method UsersTrainings[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsersTrainingsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UsersTrainings::class);
    }

    //    /**
    //     * @return UsersTrainings[] Returns an array of UsersTrainings objects
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

    //    public function findOneBySomeField($value): ?UsersTrainings
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
