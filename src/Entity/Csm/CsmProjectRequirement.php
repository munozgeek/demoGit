<?php

namespace App\Entity\Csm;

use App\Entity\App\AppUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Csm\CsmProjectRequirementRepository")
 */
class CsmProjectRequirement
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $dateCreation;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateInit;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateEnd;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $impact;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=false)
     */
    private $description;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $solution;

    /**
     * @var CsmProjectClassification
     * @ORM\ManyToOne(targetEntity="App\Entity\Csm\CsmProjectClassification")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    public $csmProjectClassification;

    /**
     * @var CsmProjectStatus
     * @ORM\ManyToOne(targetEntity="App\Entity\Csm\CsmProjectStatus")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    public $csmProjectStatus;

    /**
     * @var CsmProject
     * @ORM\ManyToOne(targetEntity="App\Entity\Csm\CsmProject", inversedBy="csmProjectRequirement")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    public $csmProject;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getDateInit(): ?\DateTimeInterface
    {
        return $this->dateInit;
    }

    public function setDateInit(?\DateTimeInterface $dateInit): self
    {
        $this->dateInit = $dateInit;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->dateEnd;
    }

    public function setDateEnd(?\DateTimeInterface $dateEnd): self
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    public function getImpact(): ?int
    {
        return $this->impact;
    }

    public function setImpact(int $impact): self
    {
        $this->impact = $impact;

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

    public function getSolution(): ?string
    {
        return $this->solution;
    }

    public function setSolution(?string $solution): self
    {
        $this->solution = $solution;

        return $this;
    }

    public function getCsmProjectClassification(): ?CsmProjectClassification
    {
        return $this->csmProjectClassification;
    }

    public function setCsmProjectClassification(?CsmProjectClassification $csmProjectClassification): self
    {
        $this->csmProjectClassification = $csmProjectClassification;

        return $this;
    }

    public function getCsmProjectStatus(): ?CsmProjectStatus
    {
        return $this->csmProjectStatus;
    }

    public function setCsmProjectStatus(?CsmProjectStatus $csmProjectStatus): self
    {
        $this->csmProjectStatus = $csmProjectStatus;

        return $this;
    }

    public function getCsmProject(): ?CsmProject
    {
        return $this->csmProject;
    }

    public function setCsmProject(?CsmProject $csmProject): self
    {
        $this->csmProject = $csmProject;

        return $this;
    }

}
