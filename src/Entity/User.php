<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Traits\CreatedAtTrait;
use App\Entity\Traits\PrimaryKeyTrait;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(
 *     fields={"email"},
 *      message="email existant"
 * )
 *
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     */
    private $id;


    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(message="Saisir mail valide ")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Le Champs est obligatoire")
     */
    private $first_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Le Champs est obligatoire")
     */
    private $last_name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="8",minMessage="Mot de passe doit contenir au minimum 8 caractères")
     *
     **/
    private $password;
    /**
     * @Assert\EqualTo (propertyPath="password",message="Non concordance des mots de passe ")
     *
     */
    private $confirm_password;

    /**
     * @ORM\Column(type="string", nullable=true,length=255)
     */
    private $google_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     */
    private $avatar;

    /**
     * @ORM\Column(type="json", nullable=true)
     *
     */
    private $roles = [];

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Length(min="8",max="8",maxMessage="Cin non valide")
     *
     */
    private $cin;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     */
    private $facebook_id;

    /**
     * @ORM\Column (type="boolean" )
     */
    private $isBanned;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $lastActivityAt;

    /**
     * @ORM\OneToMany(targetEntity=Thread::class, mappedBy="author")
     */
    private $threads;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="author")
     */
    private $messages;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="updatedBy")
     */
    private $updatedMessages;

    /**
     * @ORM\OneToMany(targetEntity=MessageLike::class, mappedBy="user", orphanRemoval=true)
     */
    private $likes;

    /**
     * @ORM\OneToMany(targetEntity=Report::class, mappedBy="reportedBy")
     */
    private $reports;

    /**
     * @ORM\OneToMany(targetEntity=Report::class, mappedBy="treatedBy")
     */
    private $treatedReports;
    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->created_at = new DateTime('now');
        $this->isBanned = false;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(?string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(?string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getGoogleId(): ?string
    {
        return $this->google_id;
    }

    public function setGoogleId(?string $google_id): self
    {
        $this->google_id = $google_id;

        return $this;
    }

    public function getConfirmPassword(): ?string
    {
        return $this->confirm_password;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getCin(): ?int
    {
        return $this->cin;
    }

    public function setCin(?int $cin): self
    {
        $this->cin = $cin;

        return $this;
    }

    public function setConfirmPassword(?string $confirm_password): self
    {
        $this->confirm_password = $confirm_password;
        return $this;
    }


    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }


    public function getRoles(): array
    {
        $roles = $this->roles;


        return array_unique($roles);
    }

    public function setRoles(?array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }
    
    public function getLastActivityAt(): ?DateTimeInterface
    {
        return $this->lastActivityAt;
    }

    public function setLastActivityAt(DateTimeInterface $lastActivityAt): self
    {
        $this->lastActivityAt = $lastActivityAt;

        return $this;
    }


    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getFacebookId(): ?string
    {
        return $this->facebook_id;
    }

    public function setFacebookId(?string $facebook_id): self
    {
        $this->facebook_id = $facebook_id;

        return $this;
    }


    public function isBanned(): ?bool
    {
        return $this->isBanned;
    }

    public function ban(): self
    {
        $this->isBanned = true;
        return $this;
    }
    public function unban(): self
    {
        $this->isBanned = false;
        return $this;
    }

}
