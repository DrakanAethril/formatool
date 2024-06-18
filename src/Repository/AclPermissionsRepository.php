<?php

namespace App\Repository;

use App\Config\UsersStatusTrainingsEnum;
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

            // Get Trainings related perms and trainings allowed list
            $userTrainings = $user->getUsersTrainings();
            if(!empty($userTrainings)) {
                foreach($userTrainings as $userTraining) {
                    //if(!empty($userTraining->getTraining()->getInactive())) { REMOVED HERE TO BE ADDED IN THE TEMPLATE MENU TO AVOID SESSION DATA PERSISTENCE VERSUS DB UPDATE
                        $rolesForTraining = $userTraining->getRoles();
                        $res['trainings'][$userTraining->getTraining()->getId()] = [
                            'status' => $userTraining->getStatus(),
                            'role' => $userTraining->getRoles(),
                        ];
                        if(!empty($rolesForTraining)) {
                            foreach($rolesForTraining as $roleForTraining) {
                                // Add perms based on user role for the current Training
                                $permsForTraining = $userTraining->generatePermsBasedOnRole($roleForTraining);
                                if(!empty($permsForTraining)) $res['perms'] = array_merge($permsForTraining, $res['perms']);
                            }
                        }
                        if(
                            !empty($userTraining->getPermissions()) &&
                            !empty(json_decode($userTraining->getPermissions(), true))
                        ) {
                            $res['perms'] = array_merge($userTraining, $res['perms']);
                        }
                    //}
                    
                }
            }

            $heritedTrainingsForMenu = [];
            // Get Places related perms and places allowed list
            $userPlaces = $user->getUsersPlaces();
            if(!empty($userPlaces)) {
                foreach($userPlaces as $userPlace) {
                    $rolesForPlace = $userPlace->getRoles();
                    //if(empty($userPlace->getPlace()->getInactive())) {
                        $res['places'][$userPlace->getPlace()->getId()] = [
                            'status' => $userPlace->getStatus(),
                            'role' => $userPlace->getRoles(),
                        ];
                        if(!empty($rolesForPlace)) {
                            foreach($rolesForPlace as $roleForPlace) {
                                // Add perms based on user role for the current Place
                                $permsForPlaces = $userPlace->generatePermsBasedOnRole($roleForPlace);
                                if(!empty($permsForPlaces)) $res['perms'] = array_merge($permsForPlaces, $res['perms']);
                            }
                        }
                        if(
                            !empty($userPlace->getPermissions()) &&
                            !empty(json_decode($userPlace->getPermissions(), true))
                        ) {
                            $res['perms'] = array_merge($permsForPlaces, $res['perms']);
                        }

                        if(
                            in_array('PLACE_ALL|ALL|'.$userPlace->getPlace()->getId(), $res['perms']) ||
                            in_array('PLACE_ALL|READ|'.$userPlace->getPlace()->getId(), $res['perms']) ||
                            in_array('PLACE_ALL_TRAININGS|ALL|'.$userPlace->getPlace()->getId(), $res['perms']) ||
                            in_array('PLACE_ALL_TRAININGS|READ|'.$userPlace->getPlace()->getId(), $res['perms'])
                        ) {
                            $allTrainingsForPlaces = $userPlace->getPlace()->getTrainings();
                            if(!empty($allTrainingsForPlaces)) {
                                foreach($allTrainingsForPlaces as $training) {
                                    if(!array_key_exists($training->getId(), $res['trainings']) ) { //&& empty($training->getInactive())
                                        $res['trainings'][$training->getId()] = [
                                            'status' => UsersStatusTrainingsEnum::ACTIVE->value,
                                            'role' => 'HERITED',
                                        ];
                                    }
                                }
                            }

                        }
                    //}
                    
                }
            }
            //if(!empty($res['perms'])) $res['perms'] = array_unique($res['perms']);
            //if(!empty($res['places'])) $res['places'] = array_unique($res['places']);    
        }
        
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
