<?php

namespace App\Entity;

use App\Repository\GerantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=GerantRepository::class)
 */
class Gerant
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=16)
     * @Assert\NotBlank(message="REQUIRED")
     */
    private $Id_gerant;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\NotBlank(message="REQUIRED")
     */
    private $Nom;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\NotBlank(message="REQUIRED")
     */
    private $Prenom;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank(message="REQUIRED")
     */
    private $Date_nais;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="REQUIRED")
     * @Assert\Email(message="Email {{value}} is not validmessage=REQUIRED")
     */
    private $Ad_Email;

    /**
     * @ORM\Column(type="string", length=8)
     * @Assert\NotBlank(message="REQUIRED")
     */
    private $Cin;

    /**
     * @ORM\OneToMany(targetEntity=Activity::class, mappedBy="gerant", orphanRemoval=true)
     * @Assert\NotBlank(message="REQUIRED")
     */
    private $Activity;

    public function __construct()
    {
        $this->Activity = new ArrayCollection();
    }


    public function getIdGerant(): ?string
    {
        return $this->Id_gerant;
    }

    public function setIdGerant(string $Id_gerant): self
    {
        $this->Id_gerant = $Id_gerant;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->Prenom;
    }

    public function setPrenom(string $Prenom): self
    {
        $this->Prenom = $Prenom;

        return $this;
    }

    public function getDateNais(): ?\DateTimeInterface
    {
        return $this->Date_nais;
    }

    public function setDateNais(\DateTimeInterface $Date_nais): self
    {
        $this->Date_nais = $Date_nais;

        return $this;
    }

    public function getAdEmail(): ?string
    {
        return $this->Ad_Email;
    }

    public function setAdEmail(string $Ad_Email): self
    {
        $this->Ad_Email = $Ad_Email;

        return $this;
    }

    public function getCin(): ?string
    {
        return $this->Cin;
    }

    public function setCin(string $Cin): self
    {
        $this->Cin = $Cin;

        return $this;
    }

    /**
     * @return Collection|Activity[]
     */
    public function getActivity(): Collection
    {
        return $this->Activity;
    }

    public function addActivity(Activity $activity): self
    {
        if (!$this->Activity->contains($activity)) {
            $this->Activity[] = $activity;
            $activity->setGerant($this);
        }

        return $this;
    }

    public function removeActivity(Activity $activity): self
    {
        if ($this->Activity->removeElement($activity)) {
            // set the owning side to null (unless already changed)
            if ($activity->getGerant() === $this) {
                $activity->setGerant(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->Id_gerant;
    }
}
