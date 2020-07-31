<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdresseController extends AbstractController
{
    /**
     * @Route("/adresse/{id}", name="adresse_utilisateur")
     */
    public function listerAdresseUtlisateur(Utilisateur $utilisateur) {

        $depotUtilisateur = $this->getDoctrine()->getRepository(Utilisateur::class);
        $utilisateurConnecte = $depotUtilisateur->find($utilisateur->getId());
        $adresses = $utilisateurConnecte->getAdresses();

        dump($utilisateurConnecte);
        dump(count($adresses));
        dump($adresses);

        foreach ($adresses as $uneAdresse){
            dump($uneAdresse);

        }

        return $this->render('adresse/listerAdresseUtlisateur.html.twig', [
            'utilisateurConecte' => $utilisateurConnecte,
            'adresses' => $adresses
        ]);
    }
}
