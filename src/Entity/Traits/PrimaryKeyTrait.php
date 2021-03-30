<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait PrimaryKeyTrait
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", options={"unsigned"=true})
     */
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}
