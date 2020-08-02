<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Entity\Utilisateur;
use App\Form\AdresseType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdresseController extends AbstractController
{
    /**
     * @Route("/adresse/{id}", name="adresse_utilisateur")
     */
    public function listerAdresseUtlisateur(Utilisateur $utilisateur, Request $request) {

        $depotUtilisateur = $this->getDoctrine()->getRepository(Utilisateur::class);
        $utilisateurConnecte = $depotUtilisateur->find($utilisateur->getId());
        $adresses = $utilisateurConnecte->getAdresses();

        dump($utilisateurConnecte);
        dump(count($adresses));
        dump($adresses);

        foreach ($adresses as $uneAdresse){
            dump($uneAdresse);

        }

        // Formilaire ajout une adresse
        $nouvelleAdresse = new Adresse();
        $formulaireNouvelleAdresse = $this->createForm(AdresseType::class, $nouvelleAdresse);
        $formulaireNouvelleAdresse->handleRequest($request);
        if ($formulaireNouvelleAdresse->isSubmitted() && $formulaireNouvelleAdresse->isValid()) {
            $nouvelleAdresse->setUtilisateur($utilisateurConnecte);
            dump($nouvelleAdresse);
            $entiteManager = $this->getDoctrine()->getManager();
            $entiteManager->persist($nouvelleAdresse);
            $entiteManager->flush();
            return $this->redirectToRoute('adresse_utilisateur', [
                'id' => $utilisateurConnecte->getId()
            ]);
        }

        return $this->render('adresse/listerAdresseUtlisateur.html.twig', [
            'utilisateurConecte' => $utilisateurConnecte,
            'adresses' => $adresses,
            'formulaireNouvelleAdresse' => $formulaireNouvelleAdresse->createView()
        ]);
    }

    /**
     * @Route("adresse/supprimer/{utilisateurId}/{id}", name="supprimer_adresse")
     */
    public function supprimerUneAdresse($utilisateurId, Adresse $adresse){

        //dump($utilisateurId);
        //dump($this->container);
        //dump($this->container->get('security.token_storage')->getToken());
        //dump($this->container->get('security.token_storage')->getToken()->getUser()->getId());
        //die();

        // Verifier que c'est l'utilisateur connecté qui va supprimer une adresse
        if($this->container->get('security.token_storage')->getToken()->getUser()->getId() == $utilisateurId) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($adresse);
            $entityManager->flush();
        }

        return $this->redirectToRoute('adresse_utilisateur', [
            'id' => $utilisateurId
        ]);
    }

}
