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


}
