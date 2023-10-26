<?php

namespace App\Repository;

use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * @extends ServiceEntityRepository<Users>
 *
 * @implements PasswordUpgraderInterface<Users>
 *
 * @method Users|null find($id, $lockMode = null, $lockVersion = null)
 * @method Users|null findOneBy(array $criteria, array $orderBy = null)
 * @method Users[]    findAll()
 * @method Users[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsersRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Users::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof Users) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function findAllByRole($role): array {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT u
            FROM App\Entity\Users u
            WHERE u.roles LIKE :role
            AND u.deleted IS NULL
            ORDER BY u.lastname ASC'
        )->setParameter('role', '%'.$role.'%');

        // returns an array of Product objects
        return $query->getResult(); 
    }

    public static function getPasswordContstraints(): array {
        return [
            new Length([
                'min' => 8,
                'max' => 100,
                'minMessage' => 'Le mot de passe est trop court. Il doit contenir au moins {{ limit }} caractères.',
                'maxMessage' => 'Le mot de passe est trop long. Il doit contenir au maximum {{ limit }} caractères.'
            ]),
            new Regex([
                'pattern' => '/^[a-z]+$/',
                'match' => false,
                'message' => 'Le mot de passe doit contenir au moins une minucule'
            ]),
            new Regex([
                'pattern' => '/^[A-Z]+$/',
                'match' => false,
                'message' => 'Le mot de passe doit contenir au moins une majuscule'
            ]),
            new Regex([
                'pattern' => '/^[0-9]+$/i',
                'match' => false,
                'message' => 'Le mot de passe doit contenir au moins un chiffre'
            ]),

        ];

        
    }

   /**
    * @return Users[] Returns an array of Users objects
    */
//    public function findAllByRole($role): array
//    {
//         $qb = $this->createQueryBuilder('u');
//         return $qb
//             ->where($qb->expr()->isNull('u.deleted'))
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Users
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
