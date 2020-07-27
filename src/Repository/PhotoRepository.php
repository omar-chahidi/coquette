<?php

namespace App\Repository;

use App\Entity\Article;
use App\Entity\Photo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Photo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Photo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Photo[]    findAll()
 * @method Photo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhotoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Photo::class);
    }

    // /**
    //  * @return Photo[] Returns an array of Photo objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Photo
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    // Photo MASTER d'un article
    public function getMasterImage(Article $article){

        // querybuider
        $qb = $this ->createQueryBuilder('p')
            ->select('p')
            ->join('p.article', 'article')
            ->where('article.id = :id')
            ->andWhere('p.master', 1)
            ->setParameter('id', $article->getId())
        ;
        return $qb->getQuery()->getResult();

    }

    //public function masterPhotoDunArticle($articleId){
    public function masterPhotoDunArticle(Article $article){
        // querybuider
        $qb = $this ->createQueryBuilder('p')
            ->select('p')
            // Jointure avec la table article
            ->join('p.article', 'article')
            ->addSelect('article')
        ;

        // Filtre sur les photo MASTER
        $qb->where($qb->expr()->in('p.master', 1));

        // filtrer sur un id article
        //$qb->andWhere($qb->expr()->in('article.id', $articleId));
        $qb->andWhere($qb->expr()->in('article.id', $article->getId()));

        return $qb->getQuery()->getResult();
    }


}
