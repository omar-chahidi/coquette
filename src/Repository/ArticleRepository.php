<?php

namespace App\Repository;

use App\Entity\Article;
use App\Entity\Categorie;
use App\Entity\Photo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    // /**
    //  * @return Article[] Returns an array of Article objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    // equivalant de findAll
    public function myFindAll()
    {
        return $this
            ->createQueryBuilder('a')
            ->getQuery()
            ->getResult();
    }


    // Depuis le repository d'Advert
    //public function getAllArticlesWithMasterImage(Categorie $categorie)
    public function getAllArticlesWithMasterImage()
    {
        $qb = $this
            ->createQueryBuilder('a')
            // on fait une jointure avec l'entité Categorie
            /**/
            ->join('a.categorie', 'ca')
            ->addSelect('ca')
                /*
            ->where('ca.titre = :titre' )
            ->setParameter('titre', 'HOMME')
            */
            ->leftJoin('a.photos', 'ph')
            ->addSelect('ph')
            ->leftJoin('a.marque', 'ma')
            ->addSelect('ma')
        ;

        // Filtrer sur les images MASTER
        $qb->where($qb->expr()->in('ph.master', 1));

                    // filtrer sur une gategorie
        //$qb->andWhere($qb->expr()->in('ca.titre', 'HOMME'));

        return $qb
            ->getQuery()
            ->getResult();
    }


    public function getAllArticlesWithMasterImageAndCtegorie()
    {
        $qb = $this
            ->createQueryBuilder('a')
            // on fait une jointure avec l'entité Categorie
            /**/
            ->join('a.categorie', 'ca')
            ->addSelect('ca')
            /*
        ->where('ca.titre = :titre' )
        ->setParameter('titre', 'HOMME')
        */
            ->leftJoin('a.photos', 'ph')
            ->addSelect('ph')
            ->leftJoin('a.marque', 'ma')
            ->addSelect('ma')

        ;


        // Filtrer sur les images MASTER
        $qb->where($qb->expr()->in('ph.master', 1));

        // filtrer sur une gategorie
        $qb->andWhere($qb->expr()->in('ca.id', 1));

        return $qb
            ->getQuery()
            ->getResult();
    }

    // getArticleWithimages
}
