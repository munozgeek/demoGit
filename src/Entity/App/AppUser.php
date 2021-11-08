<?php

namespace App\Entity\App;

use App\Entity\Csm\CsmCompany;
use App\Entity\Utility\SecurityUser;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\LegacyPasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\App\AppUserRepository")
 */
class AppUser implements UserInterface, PasswordAuthenticatedUserInterface, LegacyPasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @var string
     * @ORM\Column(type="string", length=150, nullable=false)
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    protected $cellphone;

    /**
     * @var string
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    protected $workPosition;

    /**
     * @var CsmCompany
     * @ORM\ManyToOne(targetEntity="App\Entity\Csm\CsmCompany")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    public $csmCompany;

    /**
     * @var string
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    protected $surname;

    /**
     * @var string
     * @ORM\Column(type="string", length=150, nullable=true, unique=true)
     */
    public $email;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $password;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $salt;

    /**
     * @var string
     * @ORM\Column(type="string", length=17, nullable=true)
     */
    public $ip;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $creationDate;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $flagStatus;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true, options={"default":0})
     */
    public $flagAccess;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $lastConnectionDate;

    /**
     * @var AppProfile
     * @ORM\ManyToOne(targetEntity="App\Entity\App\AppProfile")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    public $appProfile;

    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->name.' '.$this->surname;
    }

    public function __call($name, $arguments)
    {
        // TODO: Implement @method string getUserIdentifier()
    }

    public function getUserIdentifier():string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        // TODO: Implement getRoles() method.
        $role = [];
        $role[] = 'ROLE_LOGIN_TRUE';
        if($this->getAppProfile()){
            $appMenu = $this->getAppProfile()->getAppMenu();
            foreach ($appMenu AS $i) {
                $role[] = 'ROLE_'.$i->getCode();
            }
        }
        return $role;
    }

    public function getPassword(): string
    {
        // TODO: Implement getPassword() method.
        return (string) $this->password;
    }

    public function getSalt(): string
    {
        // TODO: Implement getSalt() method.
        return (string) $this->salt;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUsername(): string
    {
        // TODO: Implement getUsername() method.
        return (string) $this->email;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

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

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(?string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function setSalt(?string $salt): self
    {
        $this->salt = $salt;

        return $this;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(?string $ip): self
    {
        $this->ip = $ip;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

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

    public function getFlagAccess(): ?bool
    {
        return $this->flagAccess;
    }

    public function setFlagAccess(?bool $flagAccess): self
    {
        $this->flagAccess = $flagAccess;

        return $this;
    }

    public function getLastConnectionDate(): ?\DateTimeInterface
    {
        return $this->lastConnectionDate;
    }

    public function setLastConnectionDate(?\DateTimeInterface $lastConnectionDate): self
    {
        $this->lastConnectionDate = $lastConnectionDate;

        return $this;
    }

    public function getAppProfile(): ?AppProfile
    {
        return $this->appProfile;
    }

    public function setAppProfile(?AppProfile $appProfile): self
    {
        $this->appProfile = $appProfile;

        return $this;
    }
}
