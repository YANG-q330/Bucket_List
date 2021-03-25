<?php

namespace App\Repository;

use App\Entity\Wish;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Wish|null find($id, $lockMode = null, $lockVersion = null)
 * @method Wish|null findOneBy(array $criteria, array $orderBy = null)
 * @method Wish[]    findAll()
 * @method Wish[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WishRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Wish::class);
    }

    // /**
    //  * @return Wish[] Returns an array of Wish objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Wish
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    /**
     * @return array
     */
    public function findWishList($page):array
    {
      $queryBuilder = $this->createQueryBuilder('w');
      //Requête pour récupérer le nombre total des wishes
      $queryBuilder->select("COUNT(w)");
      $countQuery = $queryBuilder->getQuery();
      $totalResultCount = $countQuery->getSingleScalarResult();
      //2e requête pour récupérer la liste des wishes
      $queryBuilder->select("w");
      $queryBuilder->andWhere('w.isPublished = true');
      $queryBuilder->andWhere('w.likes>300');
      $queryBuilder->setMaxResults(10);
      $queryBuilder->orderBy('w.dateCreated', 'DESC');
      //Offset
      $offset = ($page-1)*20;
      $queryBuilder->setFirstResult($offset);
      $query = $queryBuilder->getQuery();
      $result = $query->getResult();
      //Retourne 2 résultats dans le tableau
      return [
          "result" =>$result,
          "totalResultCount"=>$totalResultCount
      ];






    }
}
