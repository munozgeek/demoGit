<?php

namespace App\Entity\Csm;

use App\Entity\App\AppUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Csm\CsmTypePlansRepository")
 */
class CsmTypePlans
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="text", length=150, nullable=false)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=false)
     */
    private $description;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $numberUser;

    /**
     * @var float
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=false)
     */
    private $price;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getNumberUser(): ?int
    {
        return $this->numberUser;
    }

    public function setNumberUser(int $numberUser): self
    {
        $this->numberUser = $numberUser;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

}
