<?php

namespace App\Repository;

use App\Entity\LessonSessions;
use App\Entity\Trainings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LessonSessions>
 *
 * @method LessonSessions|null find($id, $lockMode = null, $lockVersion = null)
 * @method LessonSessions|null findOneBy(array $criteria, array $orderBy = null)
 * @method LessonSessions[]    findAll()
 * @method LessonSessions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LessonSessionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LessonSessions::class);
    }

    //    /**
    //     * @return LessonSessions[] Returns an array of LessonSessions objects
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

    //    public function findOneBySomeField($value): ?LessonSessions
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function getAssignedVolumesByTopic(Trainings $training): array
    {
        $res = [];
        $allTopicsVolumes = $this->createQueryBuilder('l')
            //->indexBy('l', 'l.topic')
            ->select('SUM(l.length) AS length')
            ->addSelect('IDENTITY(l.topic) as topic')
            ->andWhere('l.training = :training')
            ->setParameter('training', $training)
            ->groupBy('l.topic')
            ->getQuery()
            ->getArrayResult();
        foreach ($allTopicsVolumes as $sid) {
            $res[$sid['topic']] = intval($sid['length']);
        }
        return $res;
    }
}
