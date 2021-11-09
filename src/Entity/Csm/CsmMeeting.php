<?php

namespace App\Entity\Csm;

use App\Entity\App\AppUser;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Csm\CsmMeetingRepository")
 */
class CsmMeeting
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
    private $address;

    /**
     * true = interna
     * false= con cliente
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $flagType;

    /**
     * true = activa
     * false= finalizada
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $flagStatus;

    /**
     * @var AppUser
     * @ORM\ManyToOne(targetEntity="App\Entity\App\AppUser")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    public $appUser;

    /**
     * @var CsmMeeting
     * @ORM\ManyToOne(targetEntity="App\Entity\Csm\CsmCompany")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    public $csmCompany;

    /**
     * @var CsmProject
     * @ORM\ManyToOne(targetEntity="App\Entity\Csm\CsmProject", inversedBy="csmMeeting")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    public $csmProject;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Csm\CsmMeetingDetails", mappedBy="csmMeeting", orphanRemoval=true)
     */
    private $csmMeetingDetails;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Csm\CsmMeetingAttendees", mappedBy="csmMeeting", orphanRemoval=true)
     */
    private $csmMeetingAttendees;

    public function __construct()
    {
        $this->csmMeetingDetails = new ArrayCollection();
        $this->csmMeetingAttendees = new ArrayCollection();
    }

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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getFlagType(): ?bool
    {
        return $this->flagType;
    }

    public function setFlagType(?bool $flagType): self
    {
        $this->flagType = $flagType;

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

    public function getAppUser(): ?AppUser
    {
        return $this->appUser;
    }

    public function setAppUser(?AppUser $appUser): self
    {
        $this->appUser = $appUser;

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
     * @return Collection|CsmMeetingDetails[]
     */
    public function getCsmMeetingDetails(): Collection
    {
        return $this->csmMeetingDetails;
    }

    public function addCsmMeetingDetail(CsmMeetingDetails $csmMeetingDetail): self
    {
        if (!$this->csmMeetingDetails->contains($csmMeetingDetail)) {
            $this->csmMeetingDetails[] = $csmMeetingDetail;
            $csmMeetingDetail->setCsmMeeting($this);
        }

        return $this;
    }

    public function removeCsmMeetingDetail(CsmMeetingDetails $csmMeetingDetail): self
    {
        if ($this->csmMeetingDetails->removeElement($csmMeetingDetail)) {
            // set the owning side to null (unless already changed)
            if ($csmMeetingDetail->getCsmMeeting() === $this) {
                $csmMeetingDetail->setCsmMeeting(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CsmMeetingAttendees[]
     */
    public function getCsmMeetingAttendees(): Collection
    {
        return $this->csmMeetingAttendees;
    }

    public function addCsmMeetingAttendee(CsmMeetingAttendees $csmMeetingAttendee): self
    {
        if (!$this->csmMeetingAttendees->contains($csmMeetingAttendee)) {
            $this->csmMeetingAttendees[] = $csmMeetingAttendee;
            $csmMeetingAttendee->setCsmMeeting($this);
        }

        return $this;
    }

    public function removeCsmMeetingAttendee(CsmMeetingAttendees $csmMeetingAttendee): self
    {
        if ($this->csmMeetingAttendees->removeElement($csmMeetingAttendee)) {
            // set the owning side to null (unless already changed)
            if ($csmMeetingAttendee->getCsmMeeting() === $this) {
                $csmMeetingAttendee->setCsmMeeting(null);
            }
        }

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
