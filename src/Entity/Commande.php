<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommandeRepository::class)
 */
class Commande
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $datCommande;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="commandes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $utilisateur;


    /**
     * @ORM\OneToOne(targetEntity=Facture::class, mappedBy="commande", cascade={"persist", "remove"})
     */
    private $facture;

    /**
     * @ORM\OneToOne(targetEntity=BonDeLivraison::class, mappedBy="commande", cascade={"persist", "remove"})
     */
    private $bonDeLivraison;

    /**
     * @ORM\OneToMany(targetEntity=CommandeProduit::class, mappedBy="commande", orphanRemoval=true)
     */
    private $commandeProduits;

    public function __construct()
    {
        $this->commandeProduits = new ArrayCollection();
    }




    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatCommande(): ?\DateTimeInterface
    {
        return $this->datCommande;
    }

    public function setDatCommande(\DateTimeInterface $datCommande): self
    {
        $this->datCommande = $datCommande;

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


    public function getFacture(): ?Facture
    {
        return $this->facture;
    }

    public function setFacture(Facture $facture): self
    {
        $this->facture = $facture;

        // set the owning side of the relation if necessary
        if ($facture->getCommande() !== $this) {
            $facture->setCommande($this);
        }

        return $this;
    }

    public function getBonDeLivraison(): ?BonDeLivraison
    {
        return $this->bonDeLivraison;
    }

    public function setBonDeLivraison(BonDeLivraison $bonDeLivraison): self
    {
        $this->bonDeLivraison = $bonDeLivraison;

        // set the owning side of the relation if necessary
        if ($bonDeLivraison->getCommande() !== $this) {
            $bonDeLivraison->setCommande($this);
        }

        return $this;
    }

    /**
     * @return Collection|CommandeProduit[]
     */
    public function getCommandeProduits(): Collection
    {
        return $this->commandeProduits;
    }

    public function addCommandeProduit(CommandeProduit $commandeProduit): self
    {
        if (!$this->commandeProduits->contains($commandeProduit)) {
            $this->commandeProduits[] = $commandeProduit;
            $commandeProduit->setCommande($this);
        }

        return $this;
    }

    public function removeCommandeProduit(CommandeProduit $commandeProduit): self
    {
        if ($this->commandeProduits->contains($commandeProduit)) {
            $this->commandeProduits->removeElement($commandeProduit);
            // set the owning side to null (unless already changed)
            if ($commandeProduit->getCommande() === $this) {
                $commandeProduit->setCommande(null);
            }
        }

        return $this;
    }

}
