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
        $res = ['perms' => [], 'places' => [], 'trainings' => []];
        if(!empty($user)) {

            // Get specific perms for user 
            $permissions = $this->findBy(['user' => $user]);
            if(!empty($permissions)) {
                foreach($permissions as $permission) {
                    $res['perms'][] = $permission->getResource().'|'.$permission->getPrivilege().'|'.$permission->getSubjectId();
                }
            }

            // Get Places related perms and places allowed list
            $userPlaces = $user->getUsersPlaces();
            if(!empty($userPlaces)) {
                foreach($userPlaces as $userPlace) {
                    $rolesForPlace = $userPlace->getRoles();
                    $res['places'][$userPlace->getPlace()->getId()] = [
                        'status' => $userPlace->getStatus(),
                        'role' => $userPlace->getRoles(),
                        'specificPerms' => $userPlace->getPermissions()
                    ];
                    if(!empty($rolesForPlace)) {
                        foreach($rolesForPlace as $roleForPlace) {
                            // Add perms based on user role for the current Place
                            $permsForPlaces = $userPlace->generatePermsBasedOnRole($roleForPlace);
                            if(!empty($permsForPlaces)) $res['perms'] = array_merge($permsForPlaces, $res['perms']);
                        }
                    }
                }
            }

            // Get Trainings related perms and trainings allowed list
            $userTrainings = $user->getUsersTrainings();
            if(!empty($userTrainings)) {
                foreach($userTrainings as $userTraining) {
                    $rolesForTraining = $userTraining->getRoles();
                    $res['trainings'][$userTraining->getTraining()->getId()] = [
                        'status' => $userTraining->getStatus(),
                        'role' => $userTraining->getRoles(),
                        'specificPerms' => $userTraining->getPermissions()
                    ];
                    if(!empty($rolesForTraining)) {
                        foreach($rolesForTraining as $roleForTraining) {
                            // Add perms based on user role for the current Training
                            $permsForTraining = $userTraining->generatePermsBasedOnRole($roleForTraining);
                            if(!empty($permsForTraining)) $res['perms'] = array_merge($permsForTraining, $res['perms']);
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
