<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

// namespace pour la validation
use Symfony\Component\Validator\Constraints as Assert;

// ajouter des méthodes pour l encodage des utilisateur
use Symfony\Component\Security\Core\User\UserInterface;

// Pour jouter un utilisateur unique via son adresse email
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UtilisateurRepository::class)
 * @UniqueEntity(
 *     fields={"email"},
 *     message="L'email que vous avez indiqué est déjà utilisé !"
 * )
 */
class Utilisateur implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Length(min=3,
     *      max=50,
     *     minMessage="Il faut un nom plus que 3 caractères",
     *     maxMessage="Il faut un nom moin que 5O caractères")
     */
    private $nomUtilisateur;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Length(min=3,
     *      max=50,
     *     minMessage="Il faut un nom plus que 3 caractères",
     *     maxMessage="Il faut un nom moin que 5O caractères")
     */
    private $prenom;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateNaissance;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Email(message = "Adresse email '{{ value }}' n'est pas validé")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=3, minMessage="Votre mot de passe doit faire minimum 3 caractères")
     */
    private $password;

    /**
     * @Assert\EqualTo(propertyPath="password", message="Vous n'avez pas tappé le même mot de passe")
     */
    public $confirm_password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $activationToken;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateAjout;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateDesactivation;

    /**
     * @ORM\OneToMany(targetEntity=Adresse::class, mappedBy="utilisateur", orphanRemoval=true)
     */
    private $adresses;

    public function __construct()
    {
        $this->adresses = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomUtilisateur(): ?string
    {
        return $this->nomUtilisateur;
    }

    public function setNomUtilisateur(string $nomUtilisateur): self
    {
        $this->nomUtilisateur = $nomUtilisateur;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(?\DateTimeInterface $dateNaissance): self
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getActivationToken()
    {
        return $this->activationToken;
    }

    /**
     * @param mixed $activationToken
     * @return Utilisateur
     */
    public function setActivationToken($activationToken)
    {
        $this->activationToken = $activationToken;
        return $this;
    }



    public function getDateAjout(): ?\DateTimeInterface
    {
        return $this->dateAjout;
    }

    public function setDateAjout(?\DateTimeInterface $dateAjout): self
    {
        $this->dateAjout = $dateAjout;

        return $this;
    }

    public function getDateDesactivation(): ?\DateTimeInterface
    {
        return $this->dateDesactivation;
    }

    public function setDateDesactivation(?\DateTimeInterface $dateDesactivation): self
    {
        $this->dateDesactivation = $dateDesactivation;

        return $this;
    }

    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @return Collection|Adresse[]
     */
    public function getAdresses(): Collection
    {
        return $this->adresses;
    }

    public function addAdress(Adresse $adress): self
    {
        if (!$this->adresses->contains($adress)) {
            $this->adresses[] = $adress;
            $adress->setUtilisateur($this);
        }

        return $this;
    }

    public function removeAdress(Adresse $adress): self
    {
        if ($this->adresses->contains($adress)) {
            $this->adresses->removeElement($adress);
            // set the owning side to null (unless already changed)
            if ($adress->getUtilisateur() === $this) {
                $adress->setUtilisateur(null);
            }
        }

        return $this;
    }
}
