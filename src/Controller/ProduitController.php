<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Categorie;
use App\Entity\Photo;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function home(Request $request){
        dump($request);
        return $this->render('produit/home.html.twig', [
            'titre' => 'Je suis la page home'
        ]);
    }

    /**
     * @Route("/produit/ListeProduits", name="liste_produits")
     */
    public function index(ArticleRepository $repo)
    {
        // exemple 0
        // $repo = $this->getDoctrine()->getRepository(Article::class);

        // exemple 1
        //$articles = $repo->findAll();
        //var_dump($articles);
        //echo $articles;

        // exemple 2
        // $articles = $repo->myFindAll();

        // exemple 3
        // $articles = $repo->getAllArticlesWithMasterImage();
        /*
        foreach ($articles as $unArticle) {
            // Vous pourriez faire une boucle dessus pour les afficher toutes
            //var_dump($unArticle->getPhotos());
            foreach ($unArticle->getPhotos() as $photo){
                var_dump($photo->getTitrePhoto());
            }
        }
        */

        // exemple 4 en fonction d'une catégorie
        // Liste des produits

        //$articles = $repo->getAllArticlesWithMasterImage();
        //$articles = $repo->getAllArticlesWithMasterImageAndCtegorie();
        $articles = $repo->getAllArticlesWithMasterImageAndCtegorie(2);
        foreach ($articles as $unArticle) {
            var_dump($unArticle->getCategorie()->getTitre());

            foreach ($unArticle->getPhotos() as $photo){
                var_dump($photo->getMaster());
            }

        }

        return $this->render('produit/listeProduits.html.twig', [
            'controller_name' => 'ProduitController',
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/produit/categorie/{categorie}", name="liste_produits_par_categorie")
     */
    public function produitsParCategorie(ArticleRepository $repo, $categorie)
    {
        var_dump($categorie);
        $articles = $repo->getAllArticlesWithMasterImageAndCtegorie($categorie);
        foreach ($articles as $unArticle) {
            var_dump($unArticle->getCategorie()->getTitre());
            foreach ($unArticle->getPhotos() as $photo){
                var_dump($photo->getMaster());
            }
        }
        return $this->render('produit/listeProduits.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/produit/categorie/{categorie}/{domaine}", name="liste_produits_par_categorie_et_domaine")
     */
    public function produitsParCategorieEtDomaine(ArticleRepository $repo, $categorie, $domaine)
    {
        var_dump($categorie);
        var_dump($domaine);
        $articles = $repo->getAllArticlesWithMasterImageByCtegorieAndDomaine($categorie, $domaine);
        foreach ($articles as $unArticle) {
            var_dump($unArticle->getCategorie()->getTitre());
            var_dump($unArticle->getDomaine()->getTitre());
            foreach ($unArticle->getPhotos() as $photo){
                var_dump($photo->getMaster());
            }
        }
        return $this->render('produit/listeProduits.html.twig', [
            'articles' => $articles
        ]);
    }


    /**
     * @Route("/produit/{id}", name="show_On_Product")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showOneProduct(ArticleRepository $repo, $id){
        // $repo = $this->getDoctrine()->getRepository(Article::class);
        $unArticle = $repo->find($id);

        return $this->render('produit/showOnProduct.html.twig', [
            'unArticle' => $unArticle
        ]);
    }

}
