<?php

namespace App\Repository;

use App\Entity\LessonTypes;
use App\Entity\Trainings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LessonTypes>
 *
 * @method LessonTypes|null find($id, $lockMode = null, $lockVersion = null)
 * @method LessonTypes|null findOneBy(array $criteria, array $orderBy = null)
 * @method LessonTypes[]    findAll()
 * @method LessonTypes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LessonTypesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LessonTypes::class);
    }

//    /**
//     * @return LessonTypes[] Returns an array of LessonTypes objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?LessonTypes
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
