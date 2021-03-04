<?php

namespace App\Entity;

use App\Repository\CampingRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
}
