<?php

namespace App\Repository;

use App\Entity\TopicsTrainings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TopicsTrainings>
 *
 * @method TopicsTrainings|null find($id, $lockMode = null, $lockVersion = null)
 * @method TopicsTrainings|null findOneBy(array $criteria, array $orderBy = null)
 * @method TopicsTrainings[]    findAll()
 * @method TopicsTrainings[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TopicsTrainingsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TopicsTrainings::class);
    }

//    /**
//     * @return TopicsTrainings[] Returns an array of TopicsTrainings objects
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

//    public function findOneBySomeField($value): ?TopicsTrainings
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
