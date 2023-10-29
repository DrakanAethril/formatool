<?php

namespace App\Repository;

use App\Entity\TimeSlotsTypes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TimeSlotsTypes>
 *
 * @method TimeSlotsTypes|null find($id, $lockMode = null, $lockVersion = null)
 * @method TimeSlotsTypes|null findOneBy(array $criteria, array $orderBy = null)
 * @method TimeSlotsTypes[]    findAll()
 * @method TimeSlotsTypes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TimeSlotsTypesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TimeSlotsTypes::class);
    }

//    /**
//     * @return TimeSlotsTypes[] Returns an array of TimeSlotsTypes objects
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

//    public function findOneBySomeField($value): ?TimeSlotsTypes
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
