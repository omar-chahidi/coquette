<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\ModifierUtilisateurType;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Pour protéger la totalité des routes gérées par ce contrôleur, nous allons lui attribuer la route "/admin"
 * en modifiant les annotations comme ceci
 * @Route("/admin", name="admin_")
 */
class AdminController extends AbstractController
{
    /**
     * Dans notre contrôleur d'administration, nous allons créer une méthode permettant de lister les utilisateurs
     * inscrits ainsi que leurs rôles. Cette méthode, que nous appellerons "listerLesUtilisateurParAdmin", par exemple, s'écrira comme suit
     *
     * @Route("/utilisateurs", name="utilisateurs")
     */
    public function listerLesUtilisateurParAdmin(UtilisateurRepository $utilisateurDepot)
    {
        return $this->render('admin/listerUtilisateurs.html.twig', [
            'utilisateurs' => $utilisateurDepot->findAll(),
        ]);
    }


    /**
     * Dans notre contrôleur, la méthode doit récupérer l'information de l'utilisateur à modifier, créer le formulaire
     * et le gérer si le formulaire est soumis.
     *
     * @Route("/utilisateurs/modifier/{id}", name="modifier_utilisateur")
     */
    public function editUser(Utilisateur $utilisateur, Request $request)
    {
        // Création un formulaire
        $form = $this->createForm(ModifierUtilisateurType::class, $utilisateur);

        // Annalyser la requette http
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($utilisateur);
            $entityManager->flush();
            //dump($request);
            //dump($utilisateur);
            //die();
            $this->addFlash('success', 'Utilisateur modifié avec succès');
            return $this->redirectToRoute('admin_utilisateurs');
        }

        //die();

        return $this->render('admin/modifierUtilisateurParAdmin.html.twig', [
            'userForm' => $form->createView(),
        ]);
    }

}
