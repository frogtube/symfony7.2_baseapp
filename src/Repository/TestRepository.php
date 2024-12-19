<?php

namespace App\Repository;

use App\Entity\Test;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Test>
 */
class TestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private readonly PaginatorInterface $paginator)
    {
        parent::__construct($registry, Test::class);
    }

    public function findPaginated(int $page, ?int $userId = null): PaginationInterface
    {
        $testPerPage = 2;

        $builer = $this->createQueryBuilder('t')
            ->leftJoin('t.category', 'c');

        if ($userId) {
            $builer->andWhere('t.createdBy = :userId')
                ->setParameter('userId', $userId);
        }

        $query = $builer->getQuery();

        return $this->paginator->paginate(
            $query, 
            $page,
            $testPerPage,
            [
                'distinct' => true,
                'sortFieldAllowList' => ['t.name', 't.id'],
            ]
        );
    }

    //    /**
    //     * @return Test[] Returns an array of Test objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Test
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
