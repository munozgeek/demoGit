<?php

namespace App\Entity\App;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\App\AppProfileOrderRepository")
 */
class AppProfileOrder
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", length=2, nullable=false)
     */
    private $orderProfile;

    /**
     * @var AppProfile
     * @ORM\ManyToOne(targetEntity="App\Entity\App\AppProfile")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $appProfile;

    /**
     * @var AppMenu
     * @ORM\ManyToOne(targetEntity="App\Entity\App\AppMenu")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $appMenuModule;

    /**
     * @var AppMenu
     * @ORM\ManyToOne(targetEntity="App\Entity\App\AppMenu")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private $appMenuSubModule;

    /**
     * @var AppMenu
     * @ORM\ManyToOne(targetEntity="App\Entity\App\AppMenu")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private $appMenuSubModule2;

    /**
     * @var AppMenu
     * @ORM\ManyToOne(targetEntity="App\Entity\App\AppMenu")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private $appMenuSubModule3;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderProfile(): ?int
    {
        return $this->orderProfile;
    }

    public function setOrderProfile(int $orderProfile): self
    {
        $this->orderProfile = $orderProfile;

        return $this;
    }

    public function getAppProfile(): ?AppProfile
    {
        return $this->appProfile;
    }

    public function setAppProfile(?AppProfile $appProfile): self
    {
        $this->appProfile = $appProfile;

        return $this;
    }

    public function getAppMenuModule(): ?AppMenu
    {
        return $this->appMenuModule;
    }

    public function setAppMenuModule(?AppMenu $appMenuModule): self
    {
        $this->appMenuModule = $appMenuModule;

        return $this;
    }

    public function getAppMenuSubModule(): ?AppMenu
    {
        return $this->appMenuSubModule;
    }

    public function setAppMenuSubModule(?AppMenu $appMenuSubModule): self
    {
        $this->appMenuSubModule = $appMenuSubModule;

        return $this;
    }

    public function getAppMenuSubModule2(): ?AppMenu
    {
        return $this->appMenuSubModule2;
    }

    public function setAppMenuSubModule2(?AppMenu $appMenuSubModule2): self
    {
        $this->appMenuSubModule2 = $appMenuSubModule2;

        return $this;
    }

    public function getAppMenuSubModule3(): ?AppMenu
    {
        return $this->appMenuSubModule3;
    }

    public function setAppMenuSubModule3(?AppMenu $appMenuSubModule3): self
    {
        $this->appMenuSubModule3 = $appMenuSubModule3;

        return $this;
    }
}
