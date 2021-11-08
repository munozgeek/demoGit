<?php

namespace App\Entity\App;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\App\AppProfileRepository")
 */
class AppProfile
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=10, nullable=false)
     */
    private $code;

    /**
     * @var string
     * @ORM\Column(type="string", length=150, nullable=false)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $flagDelete;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $flagStatus;

    /**
     * @var Collection
     * @ORM\ManyToMany(targetEntity="App\Entity\App\AppMenu", inversedBy="appProfile")
     */
    private $appMenu;

    /**
     * @var string
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $typeProfile;

    public function __construct()
    {
        $this->appMenu = new ArrayCollection();
    }

    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getFlagDelete(): ?bool
    {
        return $this->flagDelete;
    }

    public function setFlagDelete(?bool $flagDelete): self
    {
        $this->flagDelete = $flagDelete;

        return $this;
    }

    public function getFlagStatus(): ?bool
    {
        return $this->flagStatus;
    }

    public function setFlagStatus(?bool $flagStatus): self
    {
        $this->flagStatus = $flagStatus;

        return $this;
    }

    public function getTypeProfile(): ?string
    {
        return $this->typeProfile;
    }

    public function setTypeProfile(?string $typeProfile): self
    {
        $this->typeProfile = $typeProfile;

        return $this;
    }

    /**
     * @return Collection|AppMenu[]
     */
    public function getAppMenu(): Collection
    {
        return $this->appMenu;
    }

    public function addAppMenu(AppMenu $appMenu): self
    {
        if (!$this->appMenu->contains($appMenu)) {
            $this->appMenu[] = $appMenu;
        }

        return $this;
    }

    public function removeAppMenu(AppMenu $appMenu): self
    {
        $this->appMenu->removeElement($appMenu);

        return $this;
    }
}
