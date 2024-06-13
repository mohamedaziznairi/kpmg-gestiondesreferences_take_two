<?php

namespace App\Repository;

use App\Entity\Workstreams;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Workstreams>
 *
 * @method Workstreams|null find($id, $lockMode = null, $lockVersion = null)
 * @method Workstreams|null findOneBy(array $criteria, array $orderBy = null)
 * @method Workstreams[]    findAll()
 * @method Workstreams[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkstreamsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Workstreams::class);
    }

//    /**
//     * @return Workstreams[] Returns an array of Workstreams objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('w.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Workstreams
//    {
//        return $this->createQueryBuilder('w')
//            ->andWhere('w.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
