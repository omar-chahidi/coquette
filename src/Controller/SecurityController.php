<?php

namespace App\Controller;

//use App\Entity\User;
use App\Entity\Utilisateur;
use App\Form\InscriptionType;
use App\Form\RegistrationType;
use App\Form\ReinitialiserPwdType;
use App\Repository\UtilisateurRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

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
           ->htmlTemplate('emails/confirmationInscription.html.twig')

           // pass variables (name => value) to the template
           ->context([
               'utilisateur' => $utilisateur
           ])
       ;

       // Envoie d'email
       $mailer->send($email);
   }

    /**
     * Nous allons créer la route qui affichera un champ demandant l'adresse e-mail de l'utilisateur.
     * @Route("/security/demanderEmail", name="demander_email_pour_reinitialisation_pwd")
     */
   public function demanderEmailPourReinitialiserPwd(
       MailerInterface $mailer, Request $request, UtilisateurRepository $utilisateurRepository,
       TokenGeneratorInterface  $tokenGenerator){

       // On initialise le formulaire
       $form = $this->createForm(ReinitialiserPwdType::class);

       // On traite le formulaire
       $form->handleRequest($request);

       // Si le formulaire est valide
       if ($form->isSubmitted() && $form->isValid()) {

           // On récupère les données
           $donnees = $form->getData();

           // On cherche un utilisateur ayant cet e-mail
           $user = $utilisateurRepository->findOneBy([ 'email' => $donnees['email'] ]);

           // Si l'utilisateur n'existe pas
           if ($user === null) {
               // On envoie une alerte disant que l'adresse e-mail est inconnue
               $this->addFlash('danger', 'Cette adresse e-mail est inconnue');

               // On retourne au page connexion
               return $this->redirectToRoute('security_login');
           }

           // On génère un token
           $token = $tokenGenerator->generateToken();

           // On essaie d'écrire le token en base de données. S'il y a un problème on n'envoie pas l'email réinitialisation pwd
           try{
               $user->setPwdToken($token);
               $entityManager = $this->getDoctrine()->getManager();
               $entityManager->persist($user);
               $entityManager->flush();
           } catch (\Exception $e) {
               $this->addFlash('warning', 'Une erreur est survenue ' . $e->getMessage());
               return $this->redirectToRoute('security_login');
           }


           // On génère l'URL de réinitialisation de mot de passe
           $url = $this->generateUrl('reinitialiser_mot_de_passe', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);

           // On génère l'e-mail
           // Création d'email
           $email = (new Email())
               ->from('christophebuchou1984@gmail.com')
               ->to($user->getEmail())
               ->subject('Mot de passe oublié')
               ->html("Bonjour,<br><br>Une demande de réinitialisation de mot de passe a été effectuée pour 
                            le site COQUETTE.<br>Veuillez cliquer sur le lien suivant : <br>" . $url)
           ;

           // Envoie d'email
           $mailer->send($email);

           // On crée le message flash de confirmation
           $this->addFlash('success', 'E-mail de réinitialisation du mot de passe envoyé !');

           // On redirige vers la page de login
           return $this->redirectToRoute('security_login');
       }


       // On envoie le formulaire à la vue
       return $this->render('security/formulairEnvoieEmailPourReinitialisationPWD.html.twig', [
           'emailForm' => $form->createView()
       ]);
   }


    /**
     * Une fois le lien envoyé, nous devons traiter le retour en créant le contrôleur pour la route "reinitialis_mot_de_passe".
     * Cette route effectuera les actions suivantes :
     *      Vérifier si le token de l'URL correspond à un utilisateur
     *      Afficher le formulaire permettant de saisir le mot de passe
     *
     * @Route("/security/reinitialiserPWD/{token}", name="reinitialiser_mot_de_passe")
     */
    public function reinitialiserPWD(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder)
    {
        // On cherche un utilisateur avec le token donné
        $utilisateur = $this->getDoctrine()->getRepository(Utilisateur::class)->findOneBy(['pwdToken' => $token]);

        // Si l'utilisateur n'existe pas
        if ($utilisateur === null) {
            // On affiche une erreur
            $this->addFlash('danger', 'Token/utilisateur Inconnu');
            return $this->redirectToRoute('security_login');
        }

        // Si le formulaire est envoyé en méthode post
        if ($request->isMethod('POST')) {
            // On supprime le token
            $utilisateur->setPwdToken(null);

            // On chiffre le mot de passe
            $utilisateur->setPassword($passwordEncoder->encodePassword($utilisateur, $request->request->get('password')));

            // On stocke
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($utilisateur);
            $entityManager->flush();

            // On crée le message flash
            $this->addFlash('message', 'Mot de passe mis à jour. Vous pouvez maintenant se connecter');

            // On redirige vers la page de connexion
            return $this->redirectToRoute('security_login');
        }else {
            // Si on n'a pas reçu les données, on affiche le formulaire
            return $this->render('security/formulaireReinitialisationPWD.html.twig', ['token' => $token]);
        }

    }


}
