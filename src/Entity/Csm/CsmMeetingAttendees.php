<?php

namespace App\Entity\Csm;

use App\Entity\App\AppUser;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Csm\CsmMeetingAttendeesRepository")
 */
class CsmMeetingAttendees
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var AppUser
     * @ORM\ManyToOne(targetEntity="App\Entity\App\AppUser")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    public $appUser;

    /**
     * null = sin confirmar
     * false= ausente
     * true = presente
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $flagAssistant;

    /**
     * @var CsmMeeting
     * @ORM\ManyToOne(targetEntity="App\Entity\Csm\CsmMeeting", inversedBy="csmMeetingAttendees")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    public $csmMeeting;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFlagAssistant(): ?bool
    {
        return $this->flagAssistant;
    }

    public function setFlagAssistant(?bool $flagAssistant): self
    {
        $this->flagAssistant = $flagAssistant;

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

    public function getCsmMeeting(): ?CsmMeeting
    {
        return $this->csmMeeting;
    }

    public function setCsmMeeting(?CsmMeeting $csmMeeting): self
    {
        $this->csmMeeting = $csmMeeting;

        return $this;
    }


}
