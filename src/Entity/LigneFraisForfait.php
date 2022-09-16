<?php

namespace App\Entity;

use App\Repository\LigneFraisForfaitRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LigneFraisForfaitRepository::class)]
class LigneFraisForfait
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantite = null;

    #[ORM\ManyToOne(inversedBy: 'ligneFraisForfaits')]
    #[ORM\JoinColumn(nullable: false)]
    private ?FicheFrais $FicheFrais = null;

    #[ORM\ManyToOne(inversedBy: 'ligneFraisForfait')]
    #[ORM\JoinColumn(nullable: false)]
    private ?FraisForfait $fraisForfait = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getFicheFrais(): ?FicheFrais
    {
        return $this->FicheFrais;
    }

    public function setFicheFrais(?FicheFrais $FicheFrais): self
    {
        $this->FicheFrais = $FicheFrais;

        return $this;
    }

    public function getFraisForfait(): ?FraisForfait
    {
        return $this->fraisForfait;
    }

    public function setFraisForfait(?FraisForfait $fraisForfait): self
    {
        $this->fraisForfait = $fraisForfait;

        return $this;
    }
}
