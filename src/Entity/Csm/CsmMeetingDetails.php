<?php

namespace App\Entity\Csm;

use App\Entity\App\AppUser;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Csm\CsmMeetingDetailsRepository")
 */
class CsmMeetingDetails
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * 1 = tema
     * 2 = tarea
     * 3 = desicion
     * 4 = informacion
     * @ORM\Column(type="integer")
     */
    private $type;

    /**
     * @var CsmMeeting
     * @ORM\ManyToOne(targetEntity="App\Entity\Csm\CsmMeeting", inversedBy="csmMeetingDetails")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    public $csmMeeting;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Csm\CsmComments", mappedBy="csmMeetingDetails", orphanRemoval=true)
     */
    private $csmComments;

    /**
     * @var CsmMeetingDetails
     * @ORM\ManyToOne(targetEntity="App\Entity\Csm\CsmMeetingDetails",inversedBy="csmMeetingDetails")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private $csmMeetingDetail;

    /**
     * Bidirectional - One-To-Many (INVERSE SIDE)
     */
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Csm\CsmMeetingDetails", mappedBy="csmMeetingDetail", orphanRemoval=true)
     */
    private $csmMeetingDetails;

    #######
    ## Data Tema
    #######

    /**
     * @var string
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $topic;

    #######
    ## Data tarea
    #######

    /**
     * @var \DateTime
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateTask;

    /**
     * @var AppUser
     * @ORM\ManyToOne(targetEntity="App\Entity\App\AppUser")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    public $appUserTask;

    /**
     * @var string
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $nameTask;

    /**
     * @var string
     * @ORM\Column(type="text", length=150, nullable=true)
     */
    private $descriptionTask;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $progressTask;

    #######
    ## Data desicion
    #######

    /**
     * @var string
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $decisionToMake;

    /**
     * @var string
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $decisionTaken;

    #######
    ## Data informacion
    #######

    /**
     * @var string
     * @ORM\Column(type="text", length=150, nullable=true)
     */
    private $information;

    public function __construct()
    {
        $this->csmComments = new ArrayCollection();
        $this->csmMeetingDetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getTopic(): ?string
    {
        return $this->topic;
    }

    public function setTopic(?string $topic): self
    {
        $this->topic = $topic;

        return $this;
    }

    public function getDateTask(): ?\DateTimeInterface
    {
        return $this->dateTask;
    }

    public function setDateTask(?\DateTimeInterface $dateTask): self
    {
        $this->dateTask = $dateTask;

        return $this;
    }

    public function getNameTask(): ?string
    {
        return $this->nameTask;
    }

    public function setNameTask(?string $nameTask): self
    {
        $this->nameTask = $nameTask;

        return $this;
    }

    public function getProgressTask(): ?int
    {
        return $this->progressTask;
    }

    public function setProgressTask(?int $progressTask): self
    {
        $this->progressTask = $progressTask;

        return $this;
    }

    public function getDecisionToMake(): ?string
    {
        return $this->decisionToMake;
    }

    public function setDecisionToMake(?string $decisionToMake): self
    {
        $this->decisionToMake = $decisionToMake;

        return $this;
    }

    public function getDecisionTaken(): ?string
    {
        return $this->decisionTaken;
    }

    public function setDecisionTaken(?string $decisionTaken): self
    {
        $this->decisionTaken = $decisionTaken;

        return $this;
    }

    public function getInformation(): ?string
    {
        return $this->information;
    }

    public function setInformation(?string $information): self
    {
        $this->information = $information;

        return $this;
    }

    public function getCsmMeeting(): ?CsmMeeting
    {
        return $this->csmMeeting;
    }

    public function setCsmMeeting(?CsmMeeting $csmMeeting): self
    {
        $this->csmMeeting = $csmMeeting;

        return $this;
    }

    /**
     * @return Collection|CsmComments[]
     */
    public function getCsmComments(): Collection
    {
        return $this->csmComments;
    }

    public function addCsmComment(CsmComments $csmComment): self
    {
        if (!$this->csmComments->contains($csmComment)) {
            $this->csmComments[] = $csmComment;
            $csmComment->setCsmMeetingDetails($this);
        }

        return $this;
    }

    public function removeCsmComment(CsmComments $csmComment): self
    {
        if ($this->csmComments->removeElement($csmComment)) {
            // set the owning side to null (unless already changed)
            if ($csmComment->getCsmMeetingDetails() === $this) {
                $csmComment->setCsmMeetingDetails(null);
            }
        }

        return $this;
    }

    public function getAppUserTask(): ?AppUser
    {
        return $this->appUserTask;
    }

    public function setAppUserTask(?AppUser $appUserTask): self
    {
        $this->appUserTask = $appUserTask;

        return $this;
    }

    public function getDescriptionTask(): ?string
    {
        return $this->descriptionTask;
    }

    public function setDescriptionTask(?string $descriptionTask): self
    {
        $this->descriptionTask = $descriptionTask;

        return $this;
    }

    public function getCsmMeetingDetail(): ?self
    {
        return $this->csmMeetingDetail;
    }

    public function setCsmMeetingDetail(?self $csmMeetingDetail): self
    {
        $this->csmMeetingDetail = $csmMeetingDetail;

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
            $csmMeetingDetail->setCsmMeetingDetail($this);
        }

        return $this;
    }

    public function removeCsmMeetingDetail(CsmMeetingDetails $csmMeetingDetail): self
    {
        if ($this->csmMeetingDetails->removeElement($csmMeetingDetail)) {
            // set the owning side to null (unless already changed)
            if ($csmMeetingDetail->getCsmMeetingDetail() === $this) {
                $csmMeetingDetail->setCsmMeetingDetail(null);
            }
        }

        return $this;
    }


}
