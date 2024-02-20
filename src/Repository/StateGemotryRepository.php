<?php

namespace App\Repository;

use App\Entity\StateGeometry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<StateGeometry>
 *
 * @method StateGeometry|null find($id, $lockMode = null, $lockVersion = null)
 * @method StateGeometry|null findOneBy(array $criteria, array $orderBy = null)
 * @method StateGeometry[]    findAll()
 * @method StateGeometry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StateGemotryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StateGeometry::class);
    }

//    /**
//     * @return StateGemotry[] Returns an array of StateGemotry objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?StateGemotry
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
