<?php

namespace App\Repository;

use App\Entity\AclPermissions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Users;

/**
 * @extends ServiceEntityRepository<AclPermissions>
 *
 * @method AclPermissions|null find($id, $lockMode = null, $lockVersion = null)
 * @method AclPermissions|null findOneBy(array $criteria, array $orderBy = null)
 * @method AclPermissions[]    findAll()
 * @method AclPermissions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AclPermissionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AclPermissions::class);
    }

    public function generateUserPermsForSession(?Users $user): array {
        $res = [];
        if(!empty($user)) {

            // Get specific perms for user 
            $permissions = $this->findBy(['user' => $user]);
            if(!empty($permissions)) {
                foreach($permissions as $permission) {
                    $res[] = $permission->getResource().'|'.$permission->getPrivilege().'|'.$permission->getSubjectId();
                }
            }

            // Get Places related perms
            $places = $user->getUsersPlaces();
            if(!empty($places)) {
                foreach($places as $place) {
                    $rolesForPlace = $place->getRoles();
                    if(!empty($rolesForPlace)) {
                        foreach($rolesForPlace as $roleForPlace) {
                            // Add perms based on user role for the current Place
                            //dd($place->generatePermsBasedOnRole($roleForPlace));
                            $permsForPlaces = $place->generatePermsBasedOnRole($roleForPlace);
                            if(!empty($permsForPlaces)) $res = array_merge($permsForPlaces, $res);
                            //$res[] += $place->generatePermsBasedOnRole($roleForPlace);
                        }
                    }
                }
            }

            // Get Trainings related perms
            $trainings = $user->getUsersTrainings();
            if(!empty($trainings)) {
                foreach($trainings as $training) {
                    $rolesForTraining = $training->getRoles();
                    if(!empty($rolesForTraining)) {
                        foreach($rolesForTraining as $roleForTraining) {
                            // Add perms based on user role for the current Training
                            //dd($roleForTraining);
                            $permsForTraining = $training->generatePermsBasedOnRole($roleForTraining);
                            if(!empty($permsForTraining)) $res = array_merge($permsForTraining, $res);
                        }
                    }
                }
            }
        }
        //dd($res);
        return $res;
    }

//    /**
//     * @return AclPermissions[] Returns an array of AclPermissions objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AclPermissions
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
