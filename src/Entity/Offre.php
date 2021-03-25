<?php

namespace App\Entity;

use App\Repository\OffreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Captcha\Bundle\CaptchaBundle\Validator\Constraints as CaptchaAssert;

/**
 * @ORM\Entity(repositoryClass=OffreRepository::class)
 */
class Offre
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Number of places is required")
     * @Assert\Positive
     * @Assert\Range(min=4,max=10,minMessage="minimum value must be 4",maxMessage="maximum value must be 10")
     */
    private $nombre_places_offre;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank(message="Valider que la date de fin est supérieure à la date de début")
     * @Assert\Expression("this.getDateDebutOffre() < this.getDateFinOffre()")
     */
    private $date_debut_offre;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank(message="Valider que la date de fin est supérieure à la date de début")
     * @Assert\Expression("this.getDateDebutOffre() < this.getDateFinOffre()")
     */
    private $date_fin_offre;

    /**
     * @ORM\OneToMany(targetEntity=Camping::class, mappedBy="offre_id")
     */
    private $camping_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\NotBlank(message="Price is required")
     * @Assert\Positive
     * @Assert\Range(min=1,max=100,minMessage="minimum value must be 1",maxMessage="maximum value must be 100")
     */
    private $prix_offre;

    /**
     * @CaptchaAssert\ValidCaptcha(
     *      message = "CAPTCHA validation failed, try again."
     * )
     */
    protected $captchaCode;

    public function __construct()
    {
        $this->camping_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id=$id;
        return $this;
    }

    public function getNombrePlacesOffre(): ?int
    {
        return $this->nombre_places_offre;
    }

    public function setNombrePlacesOffre(int $nombre_places_offre): self
    {
        $this->nombre_places_offre = $nombre_places_offre;

        return $this;
    }

    public function getDateDebutOffre(): ?\DateTimeInterface
    {
        return $this->date_debut_offre;
    }

    public function setDateDebutOffre(\DateTimeInterface $date_debut_offre): self
    {
        $this->date_debut_offre = $date_debut_offre;

        return $this;
    }

    public function getDateFinOffre(): ?\DateTimeInterface
    {
        return $this->date_fin_offre;
    }

    public function setDateFinOffre(\DateTimeInterface $date_fin_offre): self
    {
        $this->date_fin_offre = $date_fin_offre;

        return $this;
    }

    public function getCaptchaCode()
    {
        return $this->captchaCode;
    }

    public function setCaptchaCode($captchaCode)
    {
        $this->captchaCode = $captchaCode;
    }

    /**
     * @return Collection|Camping[]
     */
    public function getCampingId(): Collection
    {
        return $this->camping_id;
    }

    public function addCampingId(Camping $campingId): self
    {
        if (!$this->camping_id->contains($campingId)) {
            $this->camping_id[] = $campingId;
            $campingId->setOffreId($this);
        }

        return $this;
    }

    public function removeCampingId(Camping $campingId): self
    {
        if ($this->camping_id->removeElement($campingId)) {
            // set the owning side to null (unless already changed)
            if ($campingId->getOffreId() === $this) {
                $campingId->setOffreId(null);
            }
        }

        return $this;
    }

    public function getPrixOffre(): ?int
    {
        return $this->prix_offre;
    }

    public function setPrixOffre(?int $prix_offre): self
    {
        $this->prix_offre = $prix_offre;

        return $this;
    }
}
