<?php

namespace App\Repository;

use App\Entity\UserPoint;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

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
    private EntityManagerInterface $entityManager;

    public function __construct(
        ManagerRegistry $registry,
        EntityManagerInterface $entityManager
    )
    {
        parent::__construct($registry, UserPoint::class);
        $this->entityManager = $entityManager;
    }

    /**
     * @throws Exception
     */
    public function storeManually(array $parameters)
    {
        $sql = "INSERT INTO user_point (municipal_id, longitude, latitude, geom, created_at, updated_at) VALUES(:municipal_id, :longitude, :latitude, ST_MakePolygon(ST_GeomFromText(:polygon)), :created_at, :updated_at)";

        try {
            $this->entityManager->getConnection()->executeQuery($sql,$parameters);
            return 'ok';
        } catch (Exception $e) {
            throw new Exception('Ops, User Point not saved.');
        }
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
