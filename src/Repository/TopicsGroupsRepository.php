<?php

namespace App\Repository;

use App\Entity\TopicsGroups;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TopicsGroups>
 *
 * @method TopicsGroups|null find($id, $lockMode = null, $lockVersion = null)
 * @method TopicsGroups|null findOneBy(array $criteria, array $orderBy = null)
 * @method TopicsGroups[]    findAll()
 * @method TopicsGroups[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TopicsGroupsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TopicsGroups::class);
    }

//    /**
//     * @return TopicsGroups[] Returns an array of TopicsGroups objects
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

//    public function findOneBySomeField($value): ?TopicsGroups
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
