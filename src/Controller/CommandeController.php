<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Entity\BonDeLivraison;
use App\Entity\Commande;
use App\Entity\CommandeProduit;
use App\Entity\Facture;
use App\Entity\Utilisateur;
use App\Entity\Variante;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class CommandeController extends AbstractController
{
    /**
     * @Route("/commande/affichier/{id}", name="afficher_les_commandes")
     */
    public function afficherCommandeUtilisateur(Utilisateur $utilisateur)
    {
        $depotCommande = $this->getDoctrine()->getRepository(Commande::class);
        $tableauCommandes = $depotCommande->findBy(array('utilisateur' => $utilisateur->getId()));

        //dump($utilisateur);
        dump($tableauCommandes);
        foreach ($tableauCommandes as $commande){
            dump($commande);
            dump($commande->getCommande());
            dump($commande->getCommande()['totalTTC']);
        }
        //die();
        return $this->render('commande/afficherLesCommandesUtilisateur.html.twig', [
            'utilisateur' => $utilisateur,
            'tableauCommandes' => $tableauCommandes,
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
     * @Route("/commande/payer", name="payer_commande")
     */
    //public function payerCommande(Request $request, \Swift_Mailer $mailer)
    public function payerCommande(Request $request, MailerInterface $mailer)
    {
        $payementStatut = 'ok';
        //$payementStatut = 'ko';

        if($payementStatut == 'ok'){
            // Création de la commande
            $this->creerCommande($request);
            $this->addFlash('success', 'Commande validée numéro ' . $this->creerCommande($request)->getContent());

            // Création de la facture sous format PDF
            $this->forward('App\Controller\FactureController::creerFactureDeLaCommandeFormatPDF', [
                'commande' => $this->creerCommande($request)->getContent()
            ]);

            // Envoyer la facture par email
            $depotCommande = $this->getDoctrine()->getRepository(Commande::class);
            $commande = $depotCommande->find($this->creerCommande($request)->getContent());
            $pdfFilepath = $this->getParameter('repertoireStockageFactures') . '/' . 'Facture_numero_' . $commande->getId() . '.pdf';

            $email = (new TemplatedEmail())
                ->from('omarchahidi@gmail.com')
                ->to($this->getUser()->getEmail())
                ->subject('Facture numéro ' . $commande->getId())
                ->attachFromPath($pdfFilepath)

                // path of the Twig template to render
                ->htmlTemplate('emails/facture.html.twig')

                // pass variables (name => value) to the template
                ->context([
                    'commande' => $commande
                ])
            ;
            $mailer->send($email);

            $this->addFlash('success', "Un mail est envoyé avec la facture en pièce jointe ");
            /*
            try {
                // On crée le message
                $message = (new \Swift_Message('Facture'))
                    // On attribue l'expéditeur
                    ->setFrom('christophebuchou1984@gmail.com')
                    // On attribue le destinataire
                    ->setTo('omarchahidi@outlook.fr')
                    // On crée le texte avec la vue
                    ->setBody(
                        $this->renderView(
                            'emails/facture.html.twig', [
                                'commande' => $commande
                            ]
                        ),
                        'text/html'
                    )
                    ->setCharset('utf-8')
                    ->attach(\Swift_Attachment::fromPath($pdfFilepath));
                ;
                $mailer->send($message);
                dump($message);
                dump($mailer);
                dump($mailer->send($message) );
                if ($mailer->send($message) == 0) {
                    $this->addFlash('warning', "Erreur lors de l'envoi du mail");
                } else {
                    $this->addFlash('success', "Un mail vient d'etre envoye !");
                }
            } catch (Exception $exception) {
                dump($exception->getMessage());
                die();
            }
            */

            $request->getSession()->remove('panier');
            $request->getSession()->remove('adresses');
            $request->getSession()->remove('commande');

            //return $this->redirectToRoute("home");
            dump( $this->getUser());
            dump( $this->getUser()->getId());
            //die();
            return $this->redirectToRoute("afficher_les_commandes", [
                'id' => $this->getUser()->getId(),
            ]);

        } else {
            // Type = info, success, warning ou danger
            $this->addFlash('danger', 'Le paiement est refusé. Essayer une nouvelle fois');
            return $this->redirectToRoute("chariot_valider");
        }
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
        //$request->getSession()->remove('commande');

        $entityManager = $this->getDoctrine()->getManager();


        // Si la session commande n'existe pas on va instancier la classe commande (créer l'objet commande)
        if( !$session->has('commande')) {
            // 1) Ajout de la commande dans DB
            $commandeObjet = new Commande();
            $commandeObjet->setDatCommande(new \DateTime());
            $commandeObjet->setUtilisateur($this->container->get('security.token_storage')->getToken()->getUser());
            $commandeObjet->setCommande($this->preparerCommande($request));
            $entityManager->persist($commandeObjet);
            $entityManager->flush();
            // mettre la commande dans la session
            $session->set('commande', $commandeObjet);

            // 2) Ajout de la ligne CommandeProduit dans DB
            $panier = $session->get('panier');
            dump($session->get('panier'));
            $depotVariante= $this->getDoctrine()->getRepository(Variante::class);
            $articlesDuPanier = $depotVariante->trouverTableauArticlesPanier(array_keys($panier));
            foreach ($articlesDuPanier as $variante){
                // Modifier le stocke
                dump($variante);
                $stocke = $variante->getStocke();
                $nouveauStocke = $stocke - $panier[$variante->getId()];
                $variante->setStocke($nouveauStocke);

                // Ajouter la nouvelle ligne de la commande produit
                $commandeProduit = new CommandeProduit();
                $commandeProduit->setCommande($commandeObjet);
                $commandeProduit->setVariante($variante);
                $commandeProduit->setPrixUnitaire($variante->getArticle()->getPrix());
                $commandeProduit->setQuantite($panier[$variante->getId()]);
                $commandeProduit->setRemiseCommande($variante->getArticle()->getRemise());
                $commandeProduit->setTvaCommande($variante->getArticle()->getTva());
                $entityManager->persist($commandeProduit);
                $entityManager->flush();
                dump($variante);
                dump($commandeProduit);
            }

            // 3) Ajout de la ligne Facture dans DB
            $facture = new Facture();
            $facture->setCommande($commandeObjet);
            $facture->setDateFacture(new \DateTime());
            $entityManager->persist($facture);
            $entityManager->flush();
            dump($facture);

            // 4) Ajout de la ligne BonLivraison dans DB
            $bonLivraison = new BonDeLivraison();
            $bonLivraison->setCommande($commandeObjet);
            $bonLivraison->setDateBonLivraison(new \DateTime());
            $entityManager->persist($bonLivraison);
            $entityManager->flush();
            dump($bonLivraison);
        } else {
            $depotCommande = $this->getDoctrine()->getRepository(Commande::class);
            $commandeObjet = $depotCommande->find($session->get('commande'));
        }

        dump($session->get('commande'));
        dump($session->get('commande')->getId());
        dump($commandeObjet);
        dump($commandeObjet->getId());

        //die();

        return new Response($commandeObjet->getId());
    }

}
