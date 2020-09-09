<?php

namespace App\Controller;

use App\Entity\Commande;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
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

    public function creerFactureDeLaCommandeFormatPDF(Commande $commande){
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->setDefaultFont('Arial'); // 'defaultFont',
        $pdfOptions->setIsPhpEnabled('true'); // isPhpEnabled

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        /*
        $html = $this->renderView('facture/editerFactureDeLaCommandeFormatPDF.html.twig', [
            'commande' => $commande
        ]);
        */
        $html = $this->renderView('facture/test.html.twig', []);

        // Load HTML to Dompdf
        //$dompdf->loadHtml('hello');
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');
        //$dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();


        // Output the generated PDF to Browser (force download)
        /*
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => true
        ]);
        */

        /**/
        // Store PDF Binary Data
        $output = $dompdf->output();

        // In this case, we want to write the file in the public directory
        $nomFacture = 'Facture_numero_' . $commande->getId() . '.pdf';
        $pdfFilepath =  $this->getParameter('repertoireStockageFactures') . '/' . $nomFacture;

        // Write file to the desired path
        file_put_contents($pdfFilepath, $output);

        // Send some text response
        //return new Response($pdfFilepath);

        dump($dompdf->getCanvas());
        dump($dompdf->getCanvas()->get_page_number());
        dump($dompdf->getCanvas()->get_page_count());
        //die();

    }

    /**
     * @Route("/facture/editerPDF/{id}", name="editer_facture_pdf")
     */
    //public function editerFactureDeLaCommandeFormatPDF(Commande $commande)
    public function editerFactureDeLaCommandeFormatPDF($id)
    {
        $depotCommande = $this->getDoctrine()->getRepository(Commande::class);
        $commande = $depotCommande->findOneBy(array('utilisateur' => $this->getUser(), 'id' => $id ) );

        if(!$commande){
            // Type = info, success, warning ou danger
            $this->addFlash('danger', 'Il y a un problème de téléchargement de la commande ou commande n\'existe pas');
            return $this->redirectToRoute("afficher_les_commandes", [
                'id' => $this->getUser()->getId(),
            ]);
        }

        //$pdfFilepath = $this->creerFactureDeLaCommandeFormatPDF($commande)->getContent();

        $pdfFilepath = $this->getParameter('repertoireStockageFactures') . '/' . 'Facture_numero_' . $commande->getId() . '.pdf';
        if (!file_exists($pdfFilepath)){
            $this->creerFactureDeLaCommandeFormatPDF($commande);
        }
        //else{dump('file existe');}
        //dump($pdfFilepath);
        //die();

        return new BinaryFileResponse($pdfFilepath);
         /**/
    }

}