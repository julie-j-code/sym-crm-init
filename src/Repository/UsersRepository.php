<?php

namespace App\Repository;

use App\Entity\PropertySearch;
use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<Users>
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

    public function add(Users $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Users $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof Users) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->add($user, true);
    }

    /**
     * @return Users[] Returns an array of Users objects
     */
    public function findBySearchFilter(PropertySearch $propertySearch): array
    {
        $qb = $this->createQueryBuilder('u');

        if ($propertySearch->getName() !== null) {
            $qb->where('u.lastName=:name')
                ->setParameter('name', $propertySearch->getName());
        }

        if ($propertySearch->getTel() !== null) {
            $telToFind=str_replace(' ','',$propertySearch->getTel());
            $telToFind=substr($telToFind,-9);
            // dd($telToFind);
            $qb->having("replace(u.tel, ' ','') LIKE :telToFind")
                ->setParameter('telToFind','%'.$telToFind.'%');
            // $qb->where('u.tel LIKE :telToFind')
            //     ->setParameter('telToFind','%'.$propertySearch->getTel().'%');              
                
        }

        return $qb->getQuery()->getResult();

    }



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
