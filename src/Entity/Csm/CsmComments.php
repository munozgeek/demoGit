<?php

namespace App\Entity\Csm;

use App\Entity\App\AppUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Csm\CsmCommentsRepository")
 */
class CsmComments
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="text", length=150, nullable=true)
     */
    private $comment;

    /**
     * @var AppUser
     * @ORM\ManyToOne(targetEntity="App\Entity\App\AppUser")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    public $appUser;

    /**
     * @var CsmMeetingDetails
     * @ORM\ManyToOne(targetEntity="App\Entity\Csm\CsmMeetingDetails", inversedBy="csmComments")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    public $csmMeetingDetails;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

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

    public function getCsmMeetingDetails(): ?CsmMeetingDetails
    {
        return $this->csmMeetingDetails;
    }

    public function setCsmMeetingDetails(?CsmMeetingDetails $csmMeetingDetails): self
    {
        $this->csmMeetingDetails = $csmMeetingDetails;

        return $this;
    }


}
