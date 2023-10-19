<?php

namespace App\Repository;

use App\Entity\TopicsTrainingsLabel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TopicsTrainingsLabel>
 *
 * @method TopicsTrainingsLabel|null find($id, $lockMode = null, $lockVersion = null)
 * @method TopicsTrainingsLabel|null findOneBy(array $criteria, array $orderBy = null)
 * @method TopicsTrainingsLabel[]    findAll()
 * @method TopicsTrainingsLabel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TopicsTrainingsLabelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TopicsTrainingsLabel::class);
    }

//    /**
//     * @return TopicsTrainingsLabel[] Returns an array of TopicsTrainingsLabel objects
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

//    public function findOneBySomeField($value): ?TopicsTrainingsLabel
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
