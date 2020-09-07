<?php

namespace App\Controller;

use App\Entity\Commande;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

// Générer des pdf avec dompdf
// On importe la classe Dompdf
use Dompdf\Dompdf;
// On inclue importe la classe qui permet de gérer ses options
use Dompdf\Options;


class FactureController extends AbstractController
{
    /**
     * @Route("/facture/afficher/{id}", name="afficher_facture")
     */
    public function afficherFactureDeLaCommande(Commande $commande)
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
        return $this->render('facture/afficherFactureDeLaCommande.html.twig', [
            'commande' => $commande,
        ]);
    }

    /**
     * @Route("/facture/editerPDF/{id}", name="editer_facture_pdf")
     */
    public function editerFactureDeLaCommandeFormatPDF(Commande $commande)
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
        /*
        return $this->render('facture/editerFactureDeLaCommandeFormatPDF.html.twig', [
            'commande' => $commande,
        ]);
        */



        // On crée une  instance pour définir les options de notre fichier pdf
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        // Pour simplifier l'affichage des images, on autorise dompdf à utiliser
        // des  url pour les nom de  fichier
        $pdfOptions->set('isRemoteEnabled', TRUE);

        // On crée une instance de dompdf avec  les options définies
        $dompdf = new Dompdf($pdfOptions);

        // Récupérez le HTML généré dans notre fichier twig
        // On demande à Symfony de générer  le code html  correspondant à
        // notre template, et on stocke ce code dans une variable
        $html = $this->renderView('facture/editerFactureDeLaCommandeFormatPDF.html.twig', [
            'commande' => $commande,
        ]);

        // On envoie le code html  à notre instance de dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        //$dompdf->setPaper('A4', 'portrait');
        $dompdf->setPaper('A4', 'landscape');

        // On demande à dompdf de générer le  pdf
        $dompdf->render();

        // Sortie du PDF généré dans le navigateur (téléchargement forcé)
        $dompdf->stream("mypdf.pdf", [
            // Générer et forcer le téléchargement de fichiers PDF
            // "Attachment" => true

            // Générer et afficher le PDF dans le navigateur
            "Attachment" => false
        ]);

    }

}
