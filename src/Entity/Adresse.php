<?php

namespace App\Entity;

use App\Repository\AdresseRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdresseRepository::class)
 */
class Adresse
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomAdresse;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenomAdresse;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $telephoneAdresse;

    /**
     * @ORM\Column(type="text")
     */
    private $adresse;

    /**
     * @ORM\ManyToOne(targetEntity=Ville::class, inversedBy="adresses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ville;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="adresses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $utilisateur;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomAdresse(): ?string
    {
        return $this->nomAdresse;
    }

    public function setNomAdresse(string $nomAdresse): self
    {
        $this->nomAdresse = $nomAdresse;

        return $this;
    }

    public function getPrenomAdresse(): ?string
    {
        return $this->prenomAdresse;
    }

    public function setPrenomAdresse(string $prenomAdresse): self
    {
        $this->prenomAdresse = $prenomAdresse;

        return $this;
    }

    public function getTelephoneAdresse(): ?string
    {
        return $this->telephoneAdresse;
    }

    public function setTelephoneAdresse(string $telephoneAdresse): self
    {
        $this->telephoneAdresse = $telephoneAdresse;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getVille(): ?Ville
    {
        return $this->ville;
    }

    public function setVille(?Ville $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }
}
