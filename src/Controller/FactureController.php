<?php

namespace App\Controller;

use App\Entity\Commande;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FactureController extends AbstractController
{
    /**
     * @Route("/facture/creer/{id}", name="creer_facture")
     */
    public function creerFactureDeLaCommande(Commande $commande)
    {
        //$depotCommande = $this->getDoctrine()->getRepository(Commande::class);
        //$commande = $depotCommande->findOneBy(array('utilisateur' => $this->getUser(), 'id' => $commande->getId() ) );

        //dump($commande);
        //die();
        return $this->render('facture/creerFactureDeLaCommande.html.twig', [
            'commande' => $commande,
        ]);
    }
}
