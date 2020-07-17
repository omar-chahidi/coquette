<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Photo;
use App\Form\PhotoType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PhotoController extends AbstractController
{
    /**
     * @Route("/image/{id}/telecharger", name="telecharger_image")
     */
    public function telechargerImage(Article $article, Request $request)
    {
        $photo = new Photo();

        // création du formulaire
        $formulairePhoto = $this->createForm(PhotoType::class, $photo);

        // Analyse de la requette http
        $formulairePhoto->handleRequest($request);

        if($formulairePhoto->isSubmitted() && $formulairePhoto->isValid()) {

            // Gestion du traitement de la photo après un téléchargement
            /**
             * @var UploadedFile $imagesTelecharger
             */
            $imagesTelecharger = $formulairePhoto->get('chargementPhoto')->getData();

            if($imagesTelecharger) {

                // Traitement lorsque il y a un téléchargement multiple
                foreach ($imagesTelecharger as $uneImage) {
                    // On change le nom du fichier pour ne pas avoir de problème : même nom, des caractères spéciaux
                    $nouveauNomPhoto = md5(uniqid()) . '.' . $uneImage->guessExtension();

                    // Déplacement de l'upload dans son dossier de destination : repertoire stockage images
                    // dans le fichier config/services.yaml  on va mettre
                    //parameters:
                    //    repertoireStockageImages: '%kernel.project_dir%/public/uploads/images'
                    $uneImage->move(
                        $this->getParameter('repertoireStockageImages'),
                        $nouveauNomPhoto
                    );

                    // remplir l'objet image
                    $photo->setArticle($article);
                    $photo->setTitrePhoto($nouveauNomPhoto);
                    //dump($image);

                    // Persister l'mage
                    $entiteManager = $this->getDoctrine()->getManager();
                    $entiteManager->persist($photo);
                    $entiteManager->flush();

                    // Redirection vers les information du produit : photos + variantes
                    return $this->redirectToRoute('ajouter_variante_photo_par_un_admin', [
                        'id' => $article->getId()
                    ]);

                } // Fin foreach


            } // Fin de if
        } // Fin de if du requette http

        return $this->render('photo/telechargerPhoto.html.twig', [
            'produit' => $article,
            'formulaireImage' => $formulairePhoto->createView()
        ]);
    }
}
