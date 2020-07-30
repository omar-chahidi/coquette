<?php

namespace App\Controller;

//use App\Entity\User;
use App\Entity\Utilisateur;
use App\Form\InscriptionType;
use App\Form\RegistrationType;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * cette fonction permet de créer et modifier un compte utilisateur
     * @Route("/security/inscription", name="security_registration")
     * @Route("/security/{id}/modification", name="security_update")
     */
    //public function inscription(Request $request, ObjectManager $manager){
    //public function inscription(Request $request, EntityManagerInterface $manager){
    //public function inscriptionEtModification(Request $request, ManagerRegistry $managerRegistry){
    //public function inscriptionEtModification(Utilisateur $utilisateur = null,Request $request, ManagerRegistry $managerRegistry){

    public function inscriptionEtModification(Utilisateur $utilisateur = null,Request $request, ManagerRegistry $managerRegistry, UserPasswordEncoderInterface $encoder){

        // Instansiation d'un utilisateur
        if(!$utilisateur){
            $utilisateur = new Utilisateur();
        }

       // Création un formulaire
       $form = $this->createForm(InscriptionType::class, $utilisateur);

       // Annalyser la requette http
       $form->handleRequest($request);

       if($form->isSubmitted() && $form->isValid()){

           // Encodage du mot de passe
           $hash = $encoder->encodePassword($utilisateur, $utilisateur->getPassword());
           // Modification du mot de passe avec le même mais encoder
           $utilisateur->setPassword($hash);

           // s'il n y pas un compte utilisateur j'ajoute la date de création
           if(!$utilisateur->getId()){
               $utilisateur ->setActivation('ok')
                            ->setDateAjout(new \DateTime())
               ;
           }

           dump($utilisateur);

           //$manager->persist($utilisateur);
           //$manager->flush();

           $em = $managerRegistry->getManager();
           $em->persist($utilisateur);
           $em->flush();

           // Après une inscription je me dérige vers la route login
           return $this->redirectToRoute('security_login');
       }

       return $this->render('security/inscription.html.twig', [
           'form' => $form->createView(),
           // utilisateur existe updateMode = true
           'updateMode' => $utilisateur->getId() !== null
       ]);
   }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/security/connexion", name="security_login")
     */
   public function login(Request $request){
       dump($request);
       return $this->render('security/login.html.twig');
   }

    /**
     * @Route("/security/deconnexion", name="security_logout")
     */
   public function logout(){

   }

}
