<?php

namespace App\Entity;

use App\Repository\BonDeLivraisonRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BonDeLivraisonRepository::class)
 */
class BonDeLivraison
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
    private $dateBonLivraison;

    /**
     * @ORM\OneToOne(targetEntity=Commande::class, inversedBy="bonDeLivraison", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $commande;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateBonLivraison(): ?\DateTimeInterface
    {
        return $this->dateBonLivraison;
    }

    public function setDateBonLivraison(\DateTimeInterface $dateBonLivraison): self
    {
        $this->dateBonLivraison = $dateBonLivraison;

        return $this;
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(Commande $commande): self
    {
        $this->commande = $commande;

        return $this;
    }
}
