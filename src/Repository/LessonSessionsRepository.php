<?php

namespace App\Repository;

use App\Entity\LessonSessions;
use App\Entity\LessonTypes;
use App\Entity\Trainings;
use DateTime;
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

    public function getTotalLengthPerTypeForTraining(Trainings $training):array {
        $res = [];

        $data = $this->createQueryBuilder('l')
            ->select('SUM(l.length) AS total')
            ->addSelect('IDENTITY(l.lessonType) as lessonType')
            ->andWhere('l.training = :training')
            ->setParameter('training', $training)
            ->groupBy('l.lessonType')
            ->getQuery()
            ->getArrayResult();

        foreach($data as $row) {
            if(empty($row['lessonType'])) $row['lessonType'] = 0;
            $res['type_'.$row['lessonType']] = $row['total'];
        }
        return $res;
    }

    public function getGlobalSessionsVolumeForTraining(Trainings $training, array $lengthPerType = null):int {
        $res = 0;
        if($lengthPerType === null) $lengthPerType = $this->getTotalLengthPerTypeForTraining($training);
        if(!empty($lengthPerType)) {
            foreach($lengthPerType as $keyVPST => $valueVPST) {
                $res += $valueVPST;
            }
        }
        return $res;
    }

    public function findSessionsBetweenDatesForTraining(Trainings $training, DateTime $startDate=null, DateTime $endDate = null) : array {
        $res = [];
        if(!empty($startDate) && !empty($endDate) && $endDate>=$startDate) {
            $res = $this->createQueryBuilder('l')
            ->andWhere('l.training = :training')
            ->setParameter('training', $training)
            ->andWhere('l.day BETWEEN :start AND :end')
            ->setParameter('start', $startDate)
            //->andWhere('l.day <= :val')
            ->setParameter('end', $endDate)
            ->orderBy('l.day', 'ASC')
            ->addOrderBy('l.startHour', 'ASC')
            ->getQuery()
            ->getResult();
        }
        return $res;
    }
}
