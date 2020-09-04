<?php

namespace App\Controller;

use App\Entity\Commande;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function preparerCommande()
    {

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
        $entityManager = $this->getDoctrine()->getManager();

        // mettre la commande dans la session

        // acceder à la session avec symfony via une requette (HttpFoundation)
        $session = $request->getSession();

        // Si la session commande n'existe pas on va instancier la classe commande (créer l'objet commande)
        if( !$session->has('commande')) {
            $commandeObjet = new Commande();
            $commandeObjet->setDatCommande(new \DateTime());
            $commandeObjet->setUtilisateur($this->container->get('security.token_storage')->getToken()->getUser());
            $entityManager->persist($commandeObjet);
            $entityManager->flush();
            $session->set('commande', $commandeObjet);
        } else {
            $depotCommande = $this->getDoctrine()->getRepository(Commande::class);
            $commandeObjet = $depotCommande->find($session->get('commande'));
        }

        dump($commandeObjet);
        die();





    }
}
