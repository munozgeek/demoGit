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


}
