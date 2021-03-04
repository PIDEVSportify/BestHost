<?php

namespace App\Entity;

use App\Repository\GerantRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GerantRepository::class)
 */
class Gerant
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=16)
     */
    private $Id_gerant;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $Nom;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $Prenom;

    /**
     * @ORM\Column(type="date")
     */
    private $Date_nais;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $Ad_Email;

    /**
     * @ORM\Column(type="string", length=8)
     */
    private $Cin;


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
}
