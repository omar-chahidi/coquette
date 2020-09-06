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

        if(!$commande){
            // Type = info, success, warning ou danger
            $this->addFlash('danger', 'Il y a un problème de téléchargement de la commande');
            return $this->redirectToRoute("afficher_les_commandes", [
                'id' => $this->getUser()->getId(),
            ]);
        }
        //dump($commande);
        //die();
        return $this->render('facture/creerFactureDeLaCommande.html.twig', [
            'commande' => $commande,
        ]);
    }

    /**
     * @Route("/facture/creerPDF/{id}", name="creer_facture_pdf")
     */
    public function creerFactureDeLaCommandeFormatPDF(Commande $commande)
    {
        //$depotCommande = $this->getDoctrine()->getRepository(Commande::class);
        //$commande = $depotCommande->findOneBy(array('utilisateur' => $this->getUser(), 'id' => $commande->getId() ) );

        if(!$commande){
            // Type = info, success, warning ou danger
            $this->addFlash('danger', 'Il y a un problème de téléchargement de la commande');
            return $this->redirectToRoute("afficher_les_commandes", [
                'id' => $this->getUser()->getId(),
            ]);
        }
        //dump($commande);
        //die();
        return $this->render('facture/creerFactureDeLaCommande.html.twig', [
            'commande' => $commande,
        ]);
    }

}
