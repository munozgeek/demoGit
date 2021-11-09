<?php

namespace App\Entity\Csm;

use App\Entity\App\AppUser;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Csm\CsmProjectRepository")
 */
class CsmProject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

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
     * @var CsmCompany
     * @ORM\ManyToOne(targetEntity="App\Entity\Csm\CsmCompany")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    public $csmCompany;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Csm\CsmMeeting", mappedBy="csmProject", orphanRemoval=true)
     */
    private $csmMeeting;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Csm\CsmProjectRequirement", mappedBy="csmProject", orphanRemoval=true)
     */
    private $csmProjectRequirement;

    public function __construct()
    {
        $this->csmMeeting = new ArrayCollection();
        $this->csmProjectRequirement = new ArrayCollection();
    }

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

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCsmCompany(): ?CsmCompany
    {
        return $this->csmCompany;
    }

    public function setCsmCompany(?CsmCompany $csmCompany): self
    {
        $this->csmCompany = $csmCompany;

        return $this;
    }

    /**
     * @return Collection|CsmMeeting[]
     */
    public function getCsmMeeting(): Collection
    {
        return $this->csmMeeting;
    }

    public function addCsmMeeting(CsmMeeting $csmMeeting): self
    {
        if (!$this->csmMeeting->contains($csmMeeting)) {
            $this->csmMeeting[] = $csmMeeting;
            $csmMeeting->setCsmProject($this);
        }

        return $this;
    }

    public function removeCsmMeeting(CsmMeeting $csmMeeting): self
    {
        if ($this->csmMeeting->removeElement($csmMeeting)) {
            // set the owning side to null (unless already changed)
            if ($csmMeeting->getCsmProject() === $this) {
                $csmMeeting->setCsmProject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CsmProjectRequirement[]
     */
    public function getCsmProjectRequirement(): Collection
    {
        return $this->csmProjectRequirement;
    }

    public function addCsmProjectRequirement(CsmProjectRequirement $csmProjectRequirement): self
    {
        if (!$this->csmProjectRequirement->contains($csmProjectRequirement)) {
            $this->csmProjectRequirement[] = $csmProjectRequirement;
            $csmProjectRequirement->setCsmProject($this);
        }

        return $this;
    }

    public function removeCsmProjectRequirement(CsmProjectRequirement $csmProjectRequirement): self
    {
        if ($this->csmProjectRequirement->removeElement($csmProjectRequirement)) {
            // set the owning side to null (unless already changed)
            if ($csmProjectRequirement->getCsmProject() === $this) {
                $csmProjectRequirement->setCsmProject(null);
            }
        }

        return $this;
    }
}
