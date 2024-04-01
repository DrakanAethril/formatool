<?php

namespace App\Repository;

use App\Entity\CursusType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CursusType>
 *
 * @method CursusType|null find($id, $lockMode = null, $lockVersion = null)
 * @method CursusType|null findOneBy(array $criteria, array $orderBy = null)
 * @method CursusType[]    findAll()
 * @method CursusType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CursusTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CursusType::class);
    }

//    /**
//     * @return CursusType[] Returns an array of CursusType objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CursusType
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
