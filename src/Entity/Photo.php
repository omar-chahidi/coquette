<?php

namespace App\Entity;

use App\Repository\PhotoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PhotoRepository::class)
 */
class Photo
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
    private $titrePhoto;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $master;

    /**
     * @ORM\ManyToOne(targetEntity=Article::class, inversedBy="photos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $article;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitrePhoto(): ?string
    {
        return $this->titrePhoto;
    }

    public function setTitrePhoto(string $titrePhoto): self
    {
        $this->titrePhoto = $titrePhoto;

        return $this;
    }

    public function getMaster(): ?string
    {
        return $this->master;
    }

    public function setMaster(string $master): self
    {
        $this->master = $master;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        $this->article = $article;

        return $this;
    }
}
