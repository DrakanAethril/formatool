<?php

namespace App\Repository;

use App\Entity\TrainingFinancialItems;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TrainingFinancialItems>
 *
 * @method TrainingFinancialItems|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrainingFinancialItems|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrainingFinancialItems[]    findAll()
 * @method TrainingFinancialItems[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrainingFinancialItemsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrainingFinancialItems::class);
    }

//    /**
//     * @return TrainingFinancialItems[] Returns an array of TrainingFinancialItems objects
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

//    public function findOneBySomeField($value): ?TrainingFinancialItems
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
