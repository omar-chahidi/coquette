<?php

namespace App\Entity;

use App\Repository\CommandeProduitRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommandeProduitRepository::class)
 */
class CommandeProduit
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", precision=7, scale=2)
     */
    private $prixUnitaire;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantite;

    /**
     * @ORM\ManyToOne(targetEntity=Commande::class, inversedBy="commandeProduits")
     * @ORM\JoinColumn(nullable=false)
     */
    private $commande;

    /**
     * @ORM\ManyToOne(targetEntity=Variante::class, inversedBy="commandeProduits")
     * @ORM\JoinColumn(nullable=false)
     */
    private $variante;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2, nullable=true)
     */
    private $tvaCommande;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $remiseCommande;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrixUnitaire(): ?string
    {
        return $this->prixUnitaire;
    }

    public function setPrixUnitaire(string $prixUnitaire): self
    {
        $this->prixUnitaire = $prixUnitaire;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): self
    {
        $this->commande = $commande;

        return $this;
    }

    public function getVariante(): ?Variante
    {
        return $this->variante;
    }

    public function setVariante(?Variante $variante): self
    {
        $this->variante = $variante;

        return $this;
    }

    public function getTvaCommande(): ?string
    {
        return $this->tvaCommande;
    }

    public function setTvaCommande(?string $tvaCommande): self
    {
        $this->tvaCommande = $tvaCommande;

        return $this;
    }

    public function getRemiseCommande(): ?int
    {
        return $this->remiseCommande;
    }

    public function setRemiseCommande(?int $remiseCommande): self
    {
        $this->remiseCommande = $remiseCommande;

        return $this;
    }
}
