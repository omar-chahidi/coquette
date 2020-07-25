<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Variante;
use App\Form\VarianteType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class VarianteController extends AbstractController
{
    /**
     * php bin/console make:form VarianteType
     * @Route("/variante/{id}/ajouter", name="ajouter_variante_aticle_par_admin", requirements={"id"="\d+"})
     */
    public function ajouterVariante(Article $article, Request $request) {

        dump($article);
        $variante = new Variante();

        if( $article->getDomaine()->getTitre() == 'ACCESSOIRES' ) {
            $variante->setTaille('null');
            $variante->setCouleur('null');
        }


        // Création du formulaire
        $formulaireVariante = $this->createForm(VarianteType::class, $variante);

        // Analyse de la requette http
        $formulaireVariante->handleRequest($request);
        if($formulaireVariante->isSubmitted() && $formulaireVariante->isValid()) {
            $variante->setArticle($article);
            dump($variante);

            $entiteManager = $this->getDoctrine()->getManager();
            $entiteManager->persist($variante);
            $entiteManager->flush();

            return $this->redirectToRoute('ajouter_variante_photo_par_un_admin', [
                'id' => $article->getId()
            ]);
        }

        return $this->render('variante/ajouterModifierVariante.html.twig', [
            'formulaireVariante' => $formulaireVariante->createView(),
            'produit' => $article,
            'mode' => 'ajouterVariante',
            'categorie' => $article->getCategorie()->getTitre(),
            'domaine' => $article->getDomaine()->getTitre()
        ]);
    }

    /**
     * php bin/console make:form VarianteType
     * @Route("/variante/{id}/modifier", name="modifier_variante_aticle_par_admin", requirements={"id"="\d+"})
     */
    public function modifierVariante(Variante $variante = null, Request $request) {

        dump($variante);

        // Création du formulaire
        $formulaireVariante = $this->createForm(VarianteType::class, $variante);

        // Analyse de la requette http
        $formulaireVariante->handleRequest($request);
        if($formulaireVariante->isSubmitted() && $formulaireVariante->isValid()) {
            $variante->setArticle($variante->getArticle());
            dump($variante);

            $entiteManager = $this->getDoctrine()->getManager();
            $entiteManager->persist($variante);
            $entiteManager->flush();

            return $this->redirectToRoute('ajouter_variante_photo_par_un_admin', [
                'id' => $variante->getArticle()->getId()
            ]);
        }

        return $this->render('variante/ajouterModifierVariante.html.twig', [
            'formulaireVariante' => $formulaireVariante->createView(),
            'produit' => $variante->getArticle(),
            'mode' => 'modifierVariante',
            'categorie' => $variante->getArticle()->getCategorie()->getTitre(),
            'domaine' => $variante->getArticle()->getDomaine()->getTitre()
        ]);
    }

    /**
     * @Route("/variante/{id}/supprimer", name="supprimer_variante_article_par_un_admin")
     */
    public function supprimerVariante(Variante $variante) {
        $entitemanager = $this->getDoctrine()->getManager();
        $entitemanager->remove($variante);
        $entitemanager->flush();

        return $this->redirectToRoute('ajouter_variante_photo_par_un_admin', [
            'id' => $variante->getArticle()->getId()
        ]);
    }

}