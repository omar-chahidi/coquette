<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Entity\Commande;
use App\Entity\Variante;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandeController extends AbstractController
{
    /**
     * @Route("/commande", name="commande")
     */
    public function index()
    {
        return $this->render('commande/index.html.twig', [
            'controller_name' => 'CommandeController',
        ]);
    }

    /**
     * Enregistrer toutes les information avant de payer
     */
    public function preparerCommande(Request $request)
    {

        // Générer un token une chaine aléatoire FAUT A TROUVER LE BON SYNTAXE
        //dump($this->container->get('security-secure_random'));


        // Initialisation
        $entityManager = $this->getDoctrine()->getManager();

        $session = $request->getSession();
        $adresses = $session->get('adresses');
        $panier = $session->get('panier');
        $tableauCommande = [];
        $totalTTC = 0;
        $totalHT = 0;

        $depotAdresse = $this->getDoctrine()->getRepository(Adresse::class);
        $depotArticle = $this->getDoctrine()->getRepository(Variante::class);
        $adreseLivraison = $depotAdresse->find($adresses['livraison']);
        $adreseFacturation = $depotAdresse->find($adresses['facturation']);
        $articlesDuPanier = $depotArticle->trouverTableauArticlesPanier(array_keys($panier));

        dump($adresses);
        dump($panier);
        dump($articlesDuPanier);
        dump($tableauCommande);

        /*
            Prix HT* = prix TTC ÷ (1 + taux de TVA)
            Prix TTC** = prix HT x (1 + taux de TVA)
        */
        foreach ($articlesDuPanier as $produit){
            $prixTTCSansRemiseUnArticle = $produit->getArticle()->getPrix();
            $prixTTCAvecRemiseUnArticle = $produit->getArticle()->getPrix() - ($produit->getArticle()->getPrix() * $produit->getArticle()->getRemise())/100 ;
            $prixTTCSousTotalDunArticle = $prixTTCAvecRemiseUnArticle * $panier[$produit->getId()];
            $prixHTSousTotalDunArticle = $prixTTCSousTotalDunArticle / (1 + $produit->getArticle()->getTva()/100);

            $totalTTC += $prixTTCSousTotalDunArticle;   $totalTTC = round($totalTTC, 2);
            $totalHT += $prixHTSousTotalDunArticle;     $totalHT = round($totalHT, 2);

            // Ajouter les informations d'un produit dans mon tableau commande
            $tableauCommande['produit'][$produit->getId()] = [
                'produit' => $produit->getArticle()->getTitre(),
                'taille' => $produit->getTaille(),
                'couleur' => $produit->getCouleur(),
                'tva' => $produit->getArticle()->getTva(),
                'remise' => $produit->getArticle()->getRemise(),
                'Qqantite' => $panier[$produit->getId()],
                'prixTTCSansRemiseUnArticle' => round($prixTTCSansRemiseUnArticle,2),
                'prixTTCAvecRemiseUnArticle' => round($prixTTCAvecRemiseUnArticle,2),
                'prixTTCSousTotalDunArticle' => round($prixTTCSousTotalDunArticle,2),
                'prixHTSousTotalDunArticle' => round($prixHTSousTotalDunArticle,2),
                'prixTVA' => round($prixTTCSousTotalDunArticle - $prixHTSousTotalDunArticle , 2)
            ];

            dump( $produit);
            dump( $produit->getArticle()->getPrix());
            dump( $produit->getArticle()->getRemise());
            dump( $produit->getArticle()->getTva());
        }

        //$tableauCommande['adreseLivraison'] = $adreseLivraison;
        //$tableauCommande['adreseFacturation'] = $adreseFacturation;
        $tableauCommande['adreseLivraison'] = [
            'nom' => $adreseLivraison->getNomAdresse(),
            'prenom' => $adreseLivraison->getPrenomAdresse(),
            'telephone' => $adreseLivraison->getTelephoneAdresse(),
            'adresse' => $adreseLivraison->getAdresse(),
            'cp' => $adreseLivraison->getVille()->getCodePostale(),
            'ville' => $adreseLivraison->getVille()->getNomVille(),
            'pays' => $adreseLivraison->getVille()->getPays()->getNom()
        ];
        $tableauCommande['adreseFacturation'] = [
            'nom' => $adreseFacturation->getNomAdresse(),
            'prenom' => $adreseFacturation->getPrenomAdresse(),
            'telephone' => $adreseFacturation->getTelephoneAdresse(),
            'adresse' => $adreseFacturation->getAdresse(),
            'cp' => $adreseFacturation->getVille()->getCodePostale(),
            'ville' => $adreseFacturation->getVille()->getNomVille(),
            'pays' => $adreseFacturation->getVille()->getPays()->getNom()
        ];

        $tableauCommande['totalTTC'] = $totalTTC;
        $tableauCommande['totalHT'] = $totalHT;
        $tableauCommande['totalTVA'] = round( $totalTTC - $totalHT, 2);
        dump($tableauCommande);

        //die();
        return $tableauCommande;
    }

    /**
     * Payer commande
     */
    public function payerCommande()
    {

    }

    /**
     * Créer commande
     * @Route("/commande/creer", name="creer_commande")
     */
    public function creerCommande(Request $request)
    {
        //dump($this->container);
        //dump($this->container->get('security.token_storage')->getToken());
        //dump($this->container->get('security.token_storage')->getToken()->getUser()->getId());
        //die();




        // acceder à la session avec symfony via une requette (HttpFoundation)
        $session = $request->getSession();


        // Si la session commande n'existe pas on va instancier la classe commande (créer l'objet commande)
        if( !$session->has('commande')) {
            $commandeObjet = new Commande();
            $commandeObjet->setDatCommande(new \DateTime());
            $commandeObjet->setUtilisateur($this->container->get('security.token_storage')->getToken()->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commandeObjet);
            $entityManager->flush();

            // mettre la commande dans la session
            $session->set('commande', $commandeObjet);
        } else {
            $depotCommande = $this->getDoctrine()->getRepository(Commande::class);
            $commandeObjet = $depotCommande->find($session->get('commande'));
        }

        dump($session->get('commande'));
        dump($session->get('commande')->getId());
        dump($commandeObjet);
        dump($commandeObjet->getId());



        $this->preparerCommande($request);
        //die();

        return new Response($commandeObjet->getId());
    }
}
