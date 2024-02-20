<?php

namespace App\Repository;

use App\Entity\MunicipalGeometry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MunicipalGeometry>
 *
 * @method MunicipalGeometry|null find($id, $lockMode = null, $lockVersion = null)
 * @method MunicipalGeometry|null findOneBy(array $criteria, array $orderBy = null)
 * @method MunicipalGeometry[]    findAll()
 * @method MunicipalGeometry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MunicipalGeometryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MunicipalGeometry::class);
    }

//    /**
//     * @return MunicipalGeometry[] Returns an array of MunicipalGeometry objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?MunicipalGeometry
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
