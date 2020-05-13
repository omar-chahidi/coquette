<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function home(){
        return $this->render('produit/home.html.twig', [
            'titre' => 'Je suis la page home'
        ]);
    }

    /**
     * @Route("/produit/ListeProduits", name="liste_produits")
     */
    public function index(ArticleRepository $repo)
    {
        // $repo = $this->getDoctrine()->getRepository(Article::class);
        $articles = $repo->findAll();

        return $this->render('produit/listeProduits.html.twig', [
            'controller_name' => 'ProduitController',
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
