<?php

namespace App\Entity\Csm;

use App\Entity\App\AppUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Csm\CsmCompanyRepository")
 */
class CsmCompany
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @var string
     * @ORM\Column(type="string", length=150, nullable=false)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $address;

    /**
     * @var CsmTypePlans
     * @ORM\ManyToOne(targetEntity="App\Entity\Csm\CsmTypePlans")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    public $csmTypePlans;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCsmTypePlans(): ?CsmTypePlans
    {
        return $this->csmTypePlans;
    }

    public function setCsmTypePlans(?CsmTypePlans $csmTypePlans): self
    {
        $this->csmTypePlans = $csmTypePlans;

        return $this;
    }


}
