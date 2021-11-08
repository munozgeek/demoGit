<?php

namespace App\Entity\Csm;

use App\Entity\App\AppUser;
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
     * @var Collection
     * @ORM\ManyToMany(targetEntity="App\Entity\App\AppUser", inversedBy="csmMeeting")
     */
    private $appUserMeetingAttendees;
}
