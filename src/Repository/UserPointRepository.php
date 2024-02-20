<?php

namespace App\Repository;

use App\Entity\UserPoint;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserPoint>
 *
 * @method UserPoint|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserPoint|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserPoint[]    findAll()
 * @method UserPoint[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserPointRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserPoint::class);
    }

//    /**
//     * @return UserPoint[] Returns an array of UserPoint objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UserPoint
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
