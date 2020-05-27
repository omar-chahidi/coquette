<?php


namespace App\Service\Chariot;


use App\Entity\Variante;
use App\Repository\VarianteRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ChariotService
{

    private $session;
    private $repo;

    /**
     * ChariotService constructor.
     * @param $session
     */
    public function __construct(SessionInterface $session, VarianteRepository $repo)
    {
        $this->session = $session;
        $this->repo = $repo;
    }


    public function ajouterUnProduitDansPannier(int $id){
        // Definition de mon pannier. Si je n'ai pas de panier mon panier est un tableau vide
        $panier = $this->session->get('panier', []);

        // Ajouter le produit. Si le produit existe dans mon panier j'ajoute ++1
        if( !empty($panier[$id])){
            $panier[$id]++;
        } else{
            $panier[$id] = 1;
        }

        // remettre mon pannier dans ma session
        $this->session->set('panier', $panier);

        // dump and dai
        // dd($session->get('panier'));
    }

    public function supprimerUnProduitDeMonPannier(int $id){
        // récuperation panier
        $panier = $this->session->get('panier', []);

        // Je supprime le produit sélctionner s'il existe dans mon panier
        if ( !empty($panier[$id])){
            unset($panier[$id]);
        }

        // Génération de la nouvelle pannier
        $this->session->set('panier', $panier);
    }

    public function recupereInformationPannier():array {
        // récuperation de mon pannier
        $panier = $this->session->get('panier', []);


        // création un tableau qui contient plus d'information à partir de mon tableau panier
        $panierAvecInfo = [];
        //$repo = $this->getDoctrine()->getRepository(Variante::class);
        foreach ( $panier as $id => $quantite ){
            $panierAvecInfo[] = [
                'produit' => $this->repo->find($id),
                'quantite' => $quantite,
            ];
        }
        //dd($panierAvecInfo);
        //dd($panierAvecInfo[0]['produit']);

        return $panierAvecInfo;
    }

    public function calculerTotalPannier():float {
        $panierAvecInfo = $this->recupereInformationPannier();

        // Calcul du total globale
        $total = 0;
        foreach ($panierAvecInfo as $item){
            // calcul du total de chaque produit
            $totalItem = $item['produit']->getArticle()->getPrix() * $item['quantite'];

            // total globale
            $total += $totalItem;
        }
        return $total;
    }

}