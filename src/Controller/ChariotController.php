<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Photo;
use App\Entity\Variante;
use App\Repository\PhotoRepository;
use App\Service\Chariot\ChariotService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ChariotController extends AbstractController
{
    /**
     * affichage de mon panier avec la récuperation de ma session
     * @Route("/chariot", name="chariot_index")
     */
    public function index(SessionInterface $session, Request $request)
    {
        // récuperation de mon pannier
        $panier = $session->get('panier', []);


        // création un tableau qui contient plus d'information à partir de mon tableau panier
        $panierAvecInfo = [];
        $repo = $this->getDoctrine()->getRepository(Variante::class);
        $repositoryPhoto = $this->getDoctrine()->getRepository(Photo::class);
        foreach ( $panier as $id => $quantite ){
            $panierAvecInfo[] = [
                'produit' => $repo->find($id),
                'quantite' => $quantite,
                'masterPhoto' => $repositoryPhoto->masterPhotoDunArticle($repo->find($id)->getArticle()),
            ];
        }
        dump($panier);
        //dd($panierAvecInfo);
        //dd($panierAvecInfo[0]['produit']);


        //$photoMaster = $repositoryPhoto->masterPhotoDunArticle(24);
        //$photoMaster = $repositoryPhoto->masterPhotoDunArticle($repo->find(44)->getArticle());
        //dump($photoMaster);

        // Calcul du total globale
        $total = 0;
        $nbArticles = 0;
        foreach ($panierAvecInfo as $item){
            // calcul du total de chaque produit
            //$totalItem = $item['produit']->getArticle()->getPrix() * $item['quantite'];
            $prixAvecRemise = $item['produit']->getArticle()->getPrix() - ($item['produit']->getArticle()->getPrix() * $item['produit']->getArticle()->getRemise())/100 ;
            $totalItem = $prixAvecRemise * $item['quantite'];


            // total globale
            $total += $totalItem;
            $nbArticles += $item['quantite'];

            //dump( $item['produit']);
            //dump($item['masterPhoto']);
            //foreach ($item['masterPhoto'] as $photo){dump($photo->getTitrePhoto());}
        }

        return $this->render('chariot/panier.html.twig', [
        //return $this->render('chariot/index.html.twig', [
            'items' => $panierAvecInfo,
            'total' => $total,
            'nbAricles' => $nbArticles
        ]);
    }
    /*
    public function index(ChariotService $chariotService)
    {
        // Information pannier
        $panierAvecInfo = $chariotService->recupereInformationPannier();

        // Calcul du total globale
        $total = $chariotService->calculerTotalPannier();

        return $this->render('chariot/ajouterModifierVariante.html.twig', [
            'items' => $panierAvecInfo,
            'total' => $total
        ]);
    }
    */

    /**
     * Ajouter un produit dans mon pannier
     * @Route("/chariot/ajouter/{id}", name="chariot_ajouter")
     */

    public function ajouter($id, SessionInterface $session){
    //public function ajouter($id, Request $request){
        // acceder à la session avec symfony avec via une requette (HttpFoundation)
        //$session = $request->getSession();

        // Déclarer ma variable session panier. Si je n'ai pas de panier mon panier est un tableau vide
        $panier = $session->get('panier', []);

        // $panier [ ID PRODUIT ] => QUANTITE

        // Ajouter le produit. Si le produit existe dans mon panier j'ajoute ++1
        if( !empty($panier[$id])){
            $panier[$id]++;
        } else{
            $panier[$id] = 1;
        }

        // remettre mon pannier dans ma session
        $session->set('panier', $panier);

        // dump and die
        // dd($session->get('panier'));

        return $this->redirectToRoute("chariot_index");
    }

    /*
       public function ajouter($id, ChariotService $chariotService){
           // Ajouter un produit
           $chariotService->ajouterUnProduitDansPannier($id);
           // Retoure à mon pannier
           return $this->redirectToRoute("chariot_index");
       }
   */

    /**
     * Supprimer un produit de mon panier
     * @Route("/chariet/supprimer/{id}", name="chariot_supprimer")
     */
    public function supprimer($id, SessionInterface $session){

        // récuperation panier
        $panier = $session->get('panier', []);

        //var_dump($panier); die();

        // Je supprime le produit sélctionner s'il existe dans mon panier ou la quantité = 0
        if ( !empty($panier[$id]) or $panier[$id]['quantite'] == 0){
            unset($panier[$id]);
        }

        // Génération de la nouvelle pannier
        $session->set('panier', $panier);

        // Retourner à la liste de mon panier
        return $this->redirectToRoute("chariot_index");
    }
    /*
    public function supprimer($id, ChariotService $chariotService){
        // Supprimer un produit
        $chariotService->supprimerUnProduitDeMonPannier($id);
        // Retourner à la liste de mon panier
        return $this->redirectToRoute("chariot_index");
    }
    */


    /**
     * Soustraire un produit dans mon pannier
     * @Route("/chariot/soustraire/{id}", name="chariot_soustraire")
     */
    public function soustraire($id, SessionInterface $session){
        //public function ajouter($id, Request $request){
        // acceder à la session avec symfony avec via une requette (HttpFoundation)
        //$session = $request->getSession();

        // Definition de mon pannier. Si je n'ai pas de panier mon panier est un tableau vide
        $panier = $session->get('panier', []);

        // Ajouter le produit. Si le produit existe dans mon panier j'ajoute ++1
        if( !empty($panier[$id])){
            $panier[$id]--;
        }

        // remettre mon pannier dans ma session
        $session->set('panier', $panier);

        // dump and die
        // dd($session->get('panier'));

        return $this->redirectToRoute("chariot_index");
    }


    /**
     * @Route("/chariot/valider", name="chariot_valider")
     */
    public function validerPanier(Request $request) {

        // Initialisation une session
        $session = $request->getSession();

        // Récupération de la session
        $panier = $session->get('panier');

        // Trouver les articles de mon pannier
        $depotArticle = $this->getDoctrine()->getRepository(Variante::class);
        $articlesDuPanier = $depotArticle->trouverTableauArticlesPanier(array_keys($panier));

        //dump($request->getSession()->get('panier'));
        //dump($articlesDuPanier);
        //die('ici');
        //dump($request);
        //dump($request->getMethod());
        //dump($request->query->get('btCommande'));
        //dump($request->request->get('btCommande'));

        // Calcul prix total de mon panier
        $prixTotalPanier = $this->calculerNombreArticles($panier, $request)['prixTotalPanier'];

        // calcul nombre d'articles dans mon panier
        $nombreArticlesPanier = $this->calculerNombreArticles($panier, $request)['nbArticles'];

        if ( $nombreArticlesPanier == 0) {
            return $this->redirectToRoute('chariot_index');
        }
        return $this->render('chariot/validerPanier.html.twig', [
            'articles' => $articlesDuPanier,
            'panier' => $panier,
            'nbArticles' => $nombreArticlesPanier,
            'total' => $prixTotalPanier
        ]);
    }

    public function calculerNombreArticles($monPanier, Request $request ) {
        $nbArticles = 0;
        $prixTotal = 0;

        // Récupération de la session panier
        $session = $request->getSession();
        $panier = $session->get('panier');

        // Récupération du dépôt variante
        $depotVariante = $this->getDoctrine()->getRepository(Variante::class);

        foreach ( $panier as $id => $quantite ) {
            // Calcul nombre articles
            $nbArticles = $nbArticles + $quantite ;

            // Calcul prix sous total pour un article (une ligne de mon panier)
            $article = $depotVariante->find($id);
            $prixAvecRemise = $article->getArticle()->getPrix() - ($article->getArticle()->getPrix() * $article->getArticle()->getRemise())/100 ;
            $prixSousTotalDunArticle = $prixAvecRemise * $quantite;

            // Prix Total du panier
            $prixTotal += $prixSousTotalDunArticle;
        }

        $tableauNbArticlesEtPrixTotal = [
            'nbArticles' => $nbArticles,
            'prixTotalPanier' => $prixTotal,
        ];

        //dump($tableauNbArticlesEtPrixTotal);

        return $tableauNbArticlesEtPrixTotal;
    }
}
