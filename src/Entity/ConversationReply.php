<?php

namespace App\Entity;

use App\Repository\ConversationReplyRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConversationReplyRepository::class)
 */
class ConversationReply
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
 /**
     * @ORM\Column(type="string", length=255)
     */
    private $conversation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reply;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConversation(): ?string
    {
        return $this->conversation;
    }

    public function setConversation(string $conversation): self
    {
        $this->conversation = $conversation;

        return $this;
    }

    public function getReply(): ?string
    {
        return $this->reply;
    }

    public function setReply(string $reply): self
    {
        $this->reply = $reply;

        return $this;
    }

    public function getUser(): ?string
    {
        return $this->user;
    }

    public function setUser(string $user): self
    {
        $this->user = $user;

        return $this;
    }
}


