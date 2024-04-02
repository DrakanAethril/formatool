<?php

namespace App\Repository;

use App\Entity\TrainingsModality;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TrainingsModality>
 *
 * @method TrainingsModality|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrainingsModality|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrainingsModality[]    findAll()
 * @method TrainingsModality[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrainingsModalityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrainingsModality::class);
    }

//    /**
//     * @return TrainingsModality[] Returns an array of TrainingsModality objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TrainingsModality
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
