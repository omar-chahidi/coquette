<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Photo;
use App\Entity\Utilisateur;
use App\Entity\Variante;
use App\Repository\PhotoRepository;
use App\Service\Chariot\ChariotService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

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
            // Type = notice, success, warning ou erreur
            $this->addFlash('success', 'Article supprimé avec succès' );
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
    public function validerPanier(Request $request, Security $security) {

        dump($request);
        // usually you'll want to make sure the user is authenticated first
        // Pour aller directement à la page de connexion
        //dump($this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY'));

        // Un racourcis pour voir l'utilisateur
        // dump($this->getUser());


        dump($this->trouverUtilisateurConnecte($security));
        if ( $this->trouverUtilisateurConnecte($security) == null) {
            $this->addFlash(
                'warning',
                'Avant de valider la commande il faut s\'inscrire si vous n\'navez pas de compte et ensuite se connecter'
            );
            return $this->redirectToRoute('chariot_index');
        }



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
        //dump($request->getMethod());
        //dump($request->query->get('btCommande'));
        //dump($request->request->get('btCommande'));

        // Calcul prix total de mon panier
        $prixTotalPanierTTC = $this->calculerNombreArticlesEtPrixTotal($panier, $request)['prixTotalPanierTTC'];
        $prixTotalPanierHT = $this->calculerNombreArticlesEtPrixTotal($panier, $request)['prixTotalPanierHT'];

        // calcul nombre d'articles dans mon panier
        $nombreArticlesPanier = $this->calculerNombreArticlesEtPrixTotal($panier, $request)['nbArticles'];

        foreach ( $panier as $id => $quantite ) {
            if ( $quantite == 0) {
                // Type = notice, success, warning ou error
                $this->addFlash('warning', 'Vous ne pouvez pas valider commande. La quantité d\'un d\'article ou total doit être > 0' );
                return $this->redirectToRoute('chariot_index');
            }
        }
        return $this->render('chariot/validerPanier.html.twig', [
            'articles' => $articlesDuPanier,
            'panier' => $panier,
            'nbArticles' => $nombreArticlesPanier,
            'totalTTC' => $prixTotalPanierTTC,
            'totalHT' => $prixTotalPanierHT
        ]);
    }

    public function calculerNombreArticlesEtPrixTotal($monPanier, Request $request ) {
        $nbArticles = 0;
        $prixTotalTTC = 0;
        $prixTotalHC = 0;

        // Récupération de la session panier
        $session = $request->getSession();
        $panier = $session->get('panier');

        // Récupération du dépôt variante
        $depotVariante = $this->getDoctrine()->getRepository(Variante::class);

        foreach ( $panier as $id => $quantite ) {
            // Calcul nombre articles
            $nbArticles = $nbArticles + $quantite ;

            // Calcul prix sous total pour un article (une ligne de mon panier)
            $variante = $depotVariante->find($id);
            $prixAvecRemise = $variante->getArticle()->getPrix() - ($variante->getArticle()->getPrix() * $variante->getArticle()->getRemise())/100 ;
            $prixSousTotalDunArticleTTC = $prixAvecRemise * $quantite;
            /*
                Prix HT* = prix TTC ÷ (1 + taux de TVA)
                Prix TTC** = prix HT x (1 + taux de TVA)
             */
            $prixSousTotalDunArticleHC = $prixSousTotalDunArticleTTC / (1 + $variante->getArticle()->getTva()/100);

            // Prix Total du panier
            $prixTotalTTC += $prixSousTotalDunArticleTTC;
            $prixTotalHC += $prixSousTotalDunArticleHC;
            $prixTotalHC = round($prixTotalHC, 2);
        }

        $tableauNbArticlesEtPrixTotal = [
            'nbArticles' => $nbArticles,
            'prixTotalPanierTTC' => $prixTotalTTC,
            'prixTotalPanierHT' => $prixTotalHC,
        ];

        //dump($tableauNbArticlesEtPrixTotal);

        return $tableauNbArticlesEtPrixTotal;
    }

    public function trouverUtilisateurConnecte(Security $security) {
        $utilisateurConnecte = $security->getUser();

        return $utilisateurConnecte;
    }
}
