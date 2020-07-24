<?php

namespace App\Controller;

use App\Entity\Variante;
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
    public function index(SessionInterface $session)
    {
        // récuperation de mon pannier
        $panier = $session->get('panier', []);


        // création un tableau qui contient plus d'information à partir de mon tableau panier
        $panierAvecInfo = [];
        $repo = $this->getDoctrine()->getRepository(Variante::class);
        foreach ( $panier as $id => $quantite ){
            $panierAvecInfo[] = [
                'produit' => $repo->find($id),
                'quantite' => $quantite,
            ];
        }
        //dd($panierAvecInfo);
        //dd($panierAvecInfo[0]['produit']);

        // Calcul du total globale
        $total = 0;
        foreach ($panierAvecInfo as $item){
            // calcul du total de chaque produit
            $totalItem = $item['produit']->getArticle()->getPrix() * $item['quantite'];

            // total globale
            $total += $totalItem;
        }

        return $this->render('chariot/index.html.twig', [
            'items' => $panierAvecInfo,
            'total' => $total
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

        // Definition de mon pannier. Si je n'ai pas de panier mon panier est un tableau vide
        $panier = $session->get('panier', []);

        // Ajouter le produit. Si le produit existe dans mon panier j'ajoute ++1
        if( !empty($panier[$id])){
            $panier[$id]++;
        } else{
            $panier[$id] = 1;
        }

        // remettre mon pannier dans ma session
        $session->set('panier', $panier);

        // dump and dai
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
    /*
    public function supprimer($id, SessionInterface $session){

        // récuperation panier
        $panier = $session->get('panier', []);

        // Je supprime le produit sélctionner s'il existe dans mon panier
        if ( !empty($panier[$id])){
            unset($panier[$id]);
        }

        // Génération de la nouvelle pannier
        $session->set('panier', $panier);

        // Retourner à la liste de mon panier
        return $this->redirectToRoute("chariot_index");
    }
    */
    public function supprimer($id, ChariotService $chariotService){
        // Supprimer un produit
        $chariotService->supprimerUnProduitDeMonPannier($id);
        // Retourner à la liste de mon panier
        return $this->redirectToRoute("chariot_index");
    }

}
