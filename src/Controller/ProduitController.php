<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Categorie;
use App\Entity\Photo;
use App\Entity\Variante;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class ProduitController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function home(Request $request){
        //dump($request);
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
        /*
        foreach ($articles as $unArticle) {
            var_dump($unArticle->getCategorie()->getTitre());
            foreach ($unArticle->getPhotos() as $photo){
                var_dump($photo->getMaster());
            }
        }
        */

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
        //var_dump($categorie);
        $articles = $repo->getAllArticlesWithMasterImageAndCtegorie($categorie);
        /*
        foreach ($articles as $unArticle) {
            var_dump($unArticle->getCategorie()->getTitre());
            foreach ($unArticle->getPhotos() as $photo){
                var_dump($photo->getMaster());
            }
        }
        */
        return $this->render('produit/listeProduits.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/produit/categorie/{categorie}/{domaine}", name="liste_produits_par_categorie_et_domaine")
     */
    public function produitsParCategorieEtDomaine(ArticleRepository $repo, $categorie, $domaine)
    {
        //var_dump($categorie);
        //var_dump($domaine);
        $articles = $repo->getAllArticlesWithMasterImageByCtegorieAndDomaine($categorie, $domaine);
        /*
        foreach ($articles as $unArticle) {
            var_dump($unArticle->getCategorie()->getTitre());
            var_dump($unArticle->getDomaine()->getTitre());
            foreach ($unArticle->getPhotos() as $photo){
                var_dump($photo->getMaster());
                var_dump($photo->getTitrePhoto());
            }
        }
        */
        return $this->render('produit/listeProduits.html.twig', [
            'articles' => $articles
        ]);
    }


    /**
     * @Route("/produit/{id}", name="show_On_Product", requirements={"id"="\d+"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showOneProduct(ArticleRepository $repo, $id){
        // Informations article (domaine, catégorie, prix, déscription)
        // $repo = $this->getDoctrine()->getRepository(Article::class);
        $unArticle = $repo->find($id);


        // Tableau variante pour un article (taille, couleur, quantitée stock)
        $repVariante = $this->getDoctrine()->getRepository(Variante::class);
        $articleVariante = $repVariante->findBy(array('article' => $unArticle));
        //dump($articleVariante);

        // Photos d'un article
        $repoPhoto = $this->getDoctrine()->getRepository(Photo::class);
        $articlePhotos = $repoPhoto->findBy(array('article' => $unArticle));
        //dump($articlePhotos);

        return $this->render('produit/showOnProduct.html.twig', [
            'unArticle' => $unArticle,
            'articleVariante' => $articleVariante,
            'articlePhotos' => $articlePhotos
        ]);
    }


    /* -----------------------------------------------------------------------------------------
    * ------------------------------------- ADMINISTATION -------------------------------------
    * -----------------------------------------------------------------------------------------
    */

    /**
     * Si nous souhaitons réserver cette route aux administrateurs, nous allons modifier l'annotation de la façon suivante
     * @IsGranted("ROLE_ADMIN")
     * @Route("/produit/listerPourAdmin", name="admin_afficher_les_articles")
     */
    public function adminAfficherLesArticles() {
        $repository = $this->getDoctrine()->getRepository(Article::class);
        $listeArticles = $repository->findAll();
        dump($listeArticles);
        return $this->render('produit/afficherLesArticlesPourAdmin.html.twig', [
            'listeDesArticles' => $listeArticles
        ]);
    }

    /**
     * Si nous souhaitons réserver cette route aux administrateurs, nous allons modifier l'annotation de la façon suivante
     * @IsGranted("ROLE_ADMIN")
     * @Route("/produit/ajouter", name="ajouter_produit_par_un_admin")
     * @Route("/produit/{id}/modifier", name="modifier_produit_par_un_admin", requirements={"id"="\d+"})
     * php bin/console make:form ProduitType
     */
    public function ajouterModifierAricleParUnAdmin(Article $article = null, Request $request) {

        if( !$article ){
            $article = new Article();
        }

        // Création du formulaire
        $formulaireArticle = $this->createForm(ArticleType::class, $article);

        // Annalyser la requette http
        $formulaireArticle->handleRequest($request);
        if($formulaireArticle->isSubmitted() && $formulaireArticle->isValid()) {
            // J'ajoute la date de création lorsque il n'y a pas l'article
            if(!$article->getId()) {
                $article->setCreatedAt(new \DateTime());
            }

            dump($article);
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('admin_afficher_les_articles');
        }

        return $this->render('produit/ajouterModifierAricleParUnAdmin.html.twig', [
            'formulaireArticle' => $formulaireArticle->createView(),
            'produitExist' => $article->getId() !== null
        ]);
    }

    /**
     * Si nous souhaitons réserver cette route aux administrateurs, nous allons modifier l'annotation de la façon suivante
     * @IsGranted("ROLE_ADMIN")
     * @Route("/produit/{id}/supprimer", name="supprimer_produit_par_un_admin", requirements={"id"="\d+"})
     */
    public function supprimerUnAricleParUnAdmin(Article $article){
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($article);
        $entityManager->flush();
        return $this->redirectToRoute('admin_afficher_les_articles');
    }

    /**
     * @Route("/produit/{id}/ajouter", name="ajouter_variante_photo_par_un_admin", requirements={"id"="\d+"})
     */
    public function ajouterVariantePhotoParUnAdmin(Article $article) {

        // Une autre méthode commune de protection des routes est une méthode php dans le contrôleur.
        // Il est possible d'ajouter la ligne suivante où nous le souhaitons dans nos méthodes de contrôleurs
        // Dans cet exemple, l'utilisateur qui n'a pas le rôle administrateur se verra interdire l'accès.
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        dump($article);

        $depotProduit = $this->getDoctrine()->getRepository(Article::class);
        $monArticle = $depotProduit->find($article);

        dump($monArticle);

        $depotImage = $this->getDoctrine()->getRepository(Photo::class);
        $imagesDeMonProduit = $depotImage->findBy(array('article' => $monArticle));

        $depotVariante = $this->getDoctrine()->getRepository(Variante::class);
        $variantesDeMonProduit = $depotVariante->findBy(array('article' => $monArticle));
        /**/


        return $this->render('produit/ajouterVariantePhotoParUnAdmin.html.twig', [
            'unArticle' => $monArticle,
            'imagesProduit' => $imagesDeMonProduit,
            'variantesProduit' => $variantesDeMonProduit
        ]);

    }

}
