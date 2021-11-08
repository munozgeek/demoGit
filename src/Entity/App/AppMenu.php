<?php

namespace App\Entity\App;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\App\AppMenuRepository")
 */
class AppMenu
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    private $code;

    /**
     * @var string
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $route;

    /**
     * @var string
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    private $icon;

    /**
     * @var string
     * @ORM\Column(type="string", length=150, nullable=false)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="string", length=150, nullable=false)
     */
    private $nameProfile;

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
    private $flagVisible;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $flagStatus;

    /**
     * @var AppMenu
     * @ORM\ManyToOne(targetEntity="App\Entity\App\AppMenu",inversedBy="appMenus")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private $appMenu;

    /**
     * Bidirectional - One-To-Many (INVERSE SIDE)
     */
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\App\AppMenu", mappedBy="appMenu", orphanRemoval=true)
     */
    private $appMenus;

    /**
     * @var Collection
     * @ORM\ManyToMany(targetEntity="App\Entity\App\AppProfile", mappedBy="appMenu", orphanRemoval=true)
     */
    private $appProfile;

    public function __construct()
    {
        $this->appMenus = new ArrayCollection();
        $this->appProfile = new ArrayCollection();
    }

    public function __toString()
    {
        // TODO: Implement __toString() method.
        return 'CÓDIGO: '.$this->code.', NOMBRE: '.$this->name;
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

    public function getRoute(): ?string
    {
        return $this->route;
    }

    public function setRoute(?string $route): self
    {
        $this->route = $route;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): self
    {
        $this->icon = $icon;

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

    public function getNameProfile(): ?string
    {
        return $this->nameProfile;
    }

    public function setNameProfile(string $nameProfile): self
    {
        $this->nameProfile = $nameProfile;

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

    public function getFlagVisible(): ?bool
    {
        return $this->flagVisible;
    }

    public function setFlagVisible(?bool $flagVisible): self
    {
        $this->flagVisible = $flagVisible;

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

    public function getAppMenu(): ?self
    {
        return $this->appMenu;
    }

    public function setAppMenu(?self $appMenu): self
    {
        $this->appMenu = $appMenu;

        return $this;
    }

    /**
     * @return Collection|AppMenu[]
     */
    public function getAppMenus(): Collection
    {
        return $this->appMenus;
    }

    public function addAppMenu(AppMenu $appMenu): self
    {
        if (!$this->appMenus->contains($appMenu)) {
            $this->appMenus[] = $appMenu;
            $appMenu->setAppMenu($this);
        }

        return $this;
    }

    public function removeAppMenu(AppMenu $appMenu): self
    {
        if ($this->appMenus->removeElement($appMenu)) {
            // set the owning side to null (unless already changed)
            if ($appMenu->getAppMenu() === $this) {
                $appMenu->setAppMenu(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|AppProfile[]
     */
    public function getAppProfile(): Collection
    {
        return $this->appProfile;
    }

    public function addAppProfile(AppProfile $appProfile): self
    {
        if (!$this->appProfile->contains($appProfile)) {
            $this->appProfile[] = $appProfile;
            $appProfile->addAppMenu($this);
        }

        return $this;
    }

    public function removeAppProfile(AppProfile $appProfile): self
    {
        if ($this->appProfile->removeElement($appProfile)) {
            $appProfile->removeAppMenu($this);
        }

        return $this;
    }
}
