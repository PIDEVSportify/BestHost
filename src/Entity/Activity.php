<?php

namespace App\Entity;
use App\Entity\ActLike;
use App\Repository\ActivityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\User;


/**
 * @ORM\Entity(repositoryClass=ActivityRepository::class)
 */
class Activity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=16)
     * @Assert\NotBlank(message="REQUIRED")
     */
    private $Id_act;

    /**
     * @ORM\Column(type="string", length=16)
     * @Assert\NotBlank(message="REQUIRED")
     */
    private $Type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="REQUIRED")
     */
    private $Description;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank(message="REQUIRED")
     */
    private $Date_val;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="REQUIRED")
     */
    private $Categorie;

    /**
     * @ORM\ManyToOne(targetEntity=Gerant::class, inversedBy="Activity")
     * @ORM\JoinColumn(nullable=true,name="Id_gerant", referencedColumnName="id_gerant")
     * @Assert\NotBlank(message="REQUIRED")
     */
    private $gerant;

    /**
     * @ORM\OneToMany(targetEntity=ActLike::class, mappedBy="post")
     */
    private $Likes;

    public function __construct()
    {
        $this->Likes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdAct(): ?string
    {
        return $this->Id_act;
    }

    public function setIdAct(string $Id_act): self
    {
        $this->Id_act = $Id_act;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->Type;
    }

    public function setType(string $Type): self
    {
        $this->Type = $Type;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(?string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getDateVal(): ?\DateTimeInterface
    {
        return $this->Date_val;
    }

    public function setDateVal(\DateTimeInterface $Date_val): self
    {
        $this->Date_val = $Date_val;

        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->Categorie;
    }

    public function setCategorie(string $Categorie): self
    {
        $this->Categorie = $Categorie;

        return $this;
    }

  public function getGerant(): ?Gerant
    {
        return $this->gerant;
    }

    public function setGerant(?Gerant $gerant): self
    {
        $this->gerant = $gerant;

        return $this;
    }

    /**
     * @return Collection|ActLike[]
     */
    public function getLikes(): Collection
    {
        return $this->Likes;
    }

    public function addLike(ActLike $like): self
    {
        if (!$this->Likes->contains($like)) {
            $this->Likes[] = $like;
            $like->setPost($this);
        }

        return $this;
    }

    public function removeLike(ActLike $like): self
    {
        if ($this->Likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getPost() === $this) {
                $like->setPost(null);
            }
        }

        return $this;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function  isLikedByUser(User $user) : bool
    {
        foreach ($this->Likes as $like){
            if($like->getUser() === $user) return true;
        }
        return false;

    }
}

