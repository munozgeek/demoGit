<?php

namespace App\Entity\Csm;

use App\Entity\App\AppUser;
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


}
