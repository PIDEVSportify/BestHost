<?php

namespace App\Entity;

use App\Repository\CampingRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Captcha\Bundle\CaptchaBundle\Validator\Constraints as CaptchaAssert;

/**
 * @ORM\Entity(repositoryClass=CampingRepository::class)
 */
class Camping
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Localisation is required")
     * @Assert\Length(max=60)
     */
    private $localisation_camping;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Description is required")
     * @Assert\Length(max=60)
     */
    private $description_camping;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Type is required")
     * @Assert\Length(max=60)
     */
    private $type_camping;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image_camping;

    /**
     * @ORM\ManyToOne(targetEntity=Offre::class, inversedBy="camping_id")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $offre_id;

    private $find_offre;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $rating_camping;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $average_rating;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $longitude_camping;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $latitude_camping;

    /**
     * @CaptchaAssert\ValidCaptcha(
     *      message = "CAPTCHA validation failed, try again."
     * )
     */
    protected $captchaCode;

    public function getCaptchaCode()
    {
        return $this->captchaCode;
    }

    public function setCaptchaCode($captchaCode)
    {
        $this->captchaCode = $captchaCode;
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

    public function getLocalisationCamping(): ?string
    {
        return $this->localisation_camping;
    }

    public function setLocalisationCamping(string $localisation_camping): self
    {
        $this->localisation_camping = $localisation_camping;

        return $this;
    }

    public function getDescriptionCamping(): ?string
    {
        return $this->description_camping;
    }

    public function setDescriptionCamping(string $description_camping): self
    {
        $this->description_camping = $description_camping;

        return $this;
    }

    public function getTypeCamping(): ?string
    {
        return $this->type_camping;
    }

    public function setTypeCamping(string $type_camping): self
    {
        $this->type_camping = $type_camping;

        return $this;
    }

    public function getImageCamping(): ?string
    {
        return $this->image_camping;
    }

    public function setImageCamping(?string $image_camping): self
    {
        $this->image_camping = $image_camping;

        return $this;
    }

    public function getOffreId(): ?Offre
    {
        return $this->offre_id;
    }

    public function setOffreId(?Offre $offre_id): self
    {
        $this->offre_id = $offre_id;

        return $this;
    }

    public function setFindOffre(int $f):self{
        $this->find_offre = $f;

        return $this;
    }

    public function getFindOffre(): ?int
    {
        return $this->find_offre;
    }

    public function getRatingCamping(): ?int
    {
        return $this->rating_camping;
    }

    public function setRatingCamping(?int $rating_camping): self
    {
        $this->rating_camping = $rating_camping;

        return $this;
    }

    public function getAverageRating(): ?float
    {
        return $this->average_rating;
    }

    public function setAverageRating(?float $average_rating): self
    {
        $this->average_rating = $average_rating;

        return $this;
    }

    public function getLongitudeCamping(): ?string
    {
        return $this->longitude_camping;
    }

    public function setLongitudeCamping(?string $longitude_camping): self
    {
        $this->longitude_camping = $longitude_camping;

        return $this;
    }

    public function getLatitudeCamping(): ?string
    {
        return $this->latitude_camping;
    }

    public function setLatitudeCamping(?string $latitude_camping): self
    {
        $this->latitude_camping = $latitude_camping;

        return $this;
    }
}
