<?php

namespace App\Controller;

//use App\Entity\User;
use App\Entity\Utilisateur;
use App\Form\InscriptionType;
use App\Form\RegistrationType;
use App\Repository\UtilisateurRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * Cette fonction permet de créer et modifier un compte utilisateur.
     * Elle premettra aussi d'envyer un email au utilisateur pour activer son compte
     * @Route("/security/inscription", name="security_registration")
     * @Route("/security/{id}/modification", name="security_update")
     */
    //public function inscription(Request $request, ObjectManager $manager){
    //public function inscription(Request $request, EntityManagerInterface $manager){
    //public function inscriptionEtModification(Request $request, ManagerRegistry $managerRegistry){
    //public function inscriptionEtModification(Utilisateur $utilisateur = null,Request $request, ManagerRegistry $managerRegistry){

    public function inscriptionEtModification(Utilisateur $utilisateur = null,Request $request, ManagerRegistry $managerRegistry, UserPasswordEncoderInterface $encoder, MailerInterface $mailer){
        // Initialisaton variable pour l'envoie de mail
        $mail = 'dejaEnvoyer';

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
           // et on génère un token ensuite on l'enregistre
           if(!$utilisateur->getId()){
               $utilisateur ->setActivationToken(md5(uniqid()))
                            ->setDateAjout(new \DateTime())
               ;
               $mail = 'aEnvoyer';
           }

           dump($utilisateur);

           //$manager->persist($utilisateur);
           //$manager->flush();

           $em = $managerRegistry->getManager();
           $em->persist($utilisateur);
           $em->flush();

           // Envoie d'email de création du compte
           dump($mail);
           //die();

           if($mail == 'aEnvoyer'){
               //$this->envyerEmailComfirmationCreationCompte($mailer, $utilisateur);
               //$this->addFlash('success', "Un mail est envoyé confirme la création du compte");
               // ajouter un envoi d'e-mail pour que l'utilisateur puisse activer son compte.
               // Création d'email
               $email = (new TemplatedEmail())
                   ->from('christophebuchou1984@gmail.com')
                   ->to($utilisateur->getEmail())
                   ->subject('Demande de validation la création du compte ' . $utilisateur->getNomUtilisateur() . ' ' . $utilisateur->getPrenom())

                   // path of the Twig template to render
                   ->htmlTemplate('emails/activationCompte.html.twig')

                   // pass variables (name => value) to the template
                   ->context([
                       'token' => $utilisateur->getActivationToken()
                   ])
               ;

               dump($utilisateur->getActivationToken());
               dump($email);
               // Envoie d'email
               $mailer->send($email);
               dump($mailer);

               // On génère un message
               $this->addFlash('success', 'Un mail a été envoyer pour demander l\'activation du compte à l\'adresse ' . $utilisateur->getEmail() . '. Il faut valider le compte avant se connecte' );
           }else{
               // Après une inscription je me dérige vers la route login
               $this->addFlash('success', "Les modifications sont pris en compte. Vous pouvez se connecter avec le compte modifié");
               return $this->redirectToRoute('security_login');
           }

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

    /**
     * Le lien dans l'e-mail devra diriger vers cette route qui vérifiera si le token existe et ensuite valider le compte correspondant.
     * @Route("/security/activation/{token}", name="activer_compte")
     */
    public function activerCompte($token, UtilisateurRepository $utilisateurRepository)
    {
        // On recherche si un utilisateur avec ce token existe dans la base de données
        $user = $utilisateurRepository->findOneBy(['activationToken' => $token]);

        // Si aucun utilisateur n'est associé à ce token
        if(!$user){
            // On renvoie une erreur 404
            throw $this->createNotFoundException('Cet utilisateur n\'existe pas');
        }

        // On supprime le token
        $user->setActivationToken(null);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        // On génère un message
        $this->addFlash('success', 'Compte activé avec succès. Vous pouvez se connecter');

        // On retourne au page connexion
        return $this->redirectToRoute('security_login');
    }


   public function envyerEmailComfirmationCreationCompte(MailerInterface $mailer, Utilisateur $utilisateur){
       // Création d'email
       $email = (new TemplatedEmail())
           ->from('omarchahidi@gmail.com')
           ->to($utilisateur->getEmail())
           ->subject('Création du compte ' . $utilisateur->getNomUtilisateur() . ' ' . $utilisateur->getPrenom())

           // path of the Twig template to render
           ->htmlTemplate('emails/inscription.html.twig')

           // pass variables (name => value) to the template
           ->context([
               'utilisateur' => $utilisateur
           ])
       ;

       // Envoie d'email
       $mailer->send($email);
   }





}
