<?php

namespace App\Repository;

use App\Entity\Objectives;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Objectives>
 *
 * @method Objectives|null find($id, $lockMode = null, $lockVersion = null)
 * @method Objectives|null findOneBy(array $criteria, array $orderBy = null)
 * @method Objectives[]    findAll()
 * @method Objectives[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ObjectivesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Objectives::class);
    }

//    /**
//     * @return Objectives[] Returns an array of Objectives objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Objectives
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
