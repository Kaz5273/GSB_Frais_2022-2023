<?php

namespace App\Entity;


use App\Repository\FicheFraisRepository;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FicheFraisRepository::class)]

class FicheFrais
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $nbJustificatifs = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateModif = null;

    #[ORM\ManyToOne(inversedBy: 'ficheFrais')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;


    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: '2')]
    private ?string $montant;

    #[ORM\ManyToOne(inversedBy: 'ficheFrais', fetch: 'EAGER')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Etat $etat;

    #[ORM\Column(length: 255)]
    private ?string $mois;

    #[ORM\OneToMany(mappedBy: 'ficheFrais', targetEntity: LigneFraisForfait::class, orphanRemoval: true, fetch: 'EAGER', cascade: ['persist'])]
    private Collection $ligneFraisForfaits;

    #[ORM\OneToMany(mappedBy: 'ficheFrais', targetEntity: LigneFraisHorsForfait::class, orphanRemoval: true, fetch: 'EAGER')]
    private Collection $ligneFraisHorsForfait;

    public function __construct()
    {
        $this->ligneFraisForfaits = new ArrayCollection();
        $this->ligneFraisHorsForfait = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbJustificatifs(): ?int
    {
        return $this->nbJustificatifs;
    }

    public function setNbJustificatifs(int $nbJustificatifs): self
    {
        $this->nbJustificatifs = $nbJustificatifs;

        return $this;
    }

    public function getDateModif(): ?\DateTimeInterface
    {
        return $this->dateModif;
    }

    public function setDateModif(\DateTimeInterface $dateModif): self
    {
        $this->dateModif = $dateModif;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }


    public function getMontant(): ?string
    {
        return $this->montant;
    }

    public function setMontant(string $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getEtat(): ?Etat
    {
        return $this->etat;
    }

    public function setEtat(?Etat $etat): self
    {
        $this->etat = $etat;

        return $this;
    }



    public function getMois(): ?string
    {
        return $this->mois;
    }

    public function setMois(string $mois): self
    {
        $this->mois = $mois;

        return $this;
    }

    /**
     * @return Collection<int, LigneFraisForfait>
     */
    public function getLigneFraisForfaits(): Collection
    {
        return $this->ligneFraisForfaits;
    }

    public function addLigneFraisForfait(LigneFraisForfait $ligneFraisForfait): self
    {
        if (!$this->ligneFraisForfaits->contains($ligneFraisForfait)) {
            $this->ligneFraisForfaits->add($ligneFraisForfait);
            $ligneFraisForfait->setFicheFrais($this);
        }

        return $this;
    }

    public function removeLigneFraisForfait(LigneFraisForfait $ligneFraisForfait): self
    {
        if ($this->ligneFraisForfaits->removeElement($ligneFraisForfait)) {
            // set the owning side to null (unless already changed)
            if ($ligneFraisForfait->getFicheFrais() === $this) {
                $ligneFraisForfait->setFicheFrais(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, LigneFraisHorsForfait>
     */
    public function getLigneFraisHorsForfait(): Collection
    {
        return $this->ligneFraisHorsForfait;
    }

    public function addLigneFraisHorsForfait(LigneFraisHorsForfait $ligneFraisHorsForfait): self
    {
        if (!$this->ligneFraisHorsForfait->contains($ligneFraisHorsForfait)) {
            $this->ligneFraisHorsForfait->add($ligneFraisHorsForfait);
            $ligneFraisHorsForfait->setFicheFrais($this);
        }

        return $this;
    }

    public function removeLigneFraisHorsForfait(LigneFraisHorsForfait $ligneFraisHorsForfait): self
    {
        if ($this->ligneFraisHorsForfait->removeElement($ligneFraisHorsForfait)) {
            // set the owning side to null (unless already changed)
            if ($ligneFraisHorsForfait->getFicheFrais() === $this) {
                $ligneFraisHorsForfait->setFicheFrais(null);
            }
        }

        return $this;
    }
}
