<?php

namespace App\Entity\Utility;

use App\Entity\App\AppProfile;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

trait SecurityUser
{
    #> Metodos para la creacion del usuario
    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=true, options={"default":0})
     */
    public $flagAccess;

    /**
     * @var string
     * @ORM\Column(type="string", length=150, nullable=true, unique=true)
     */
    public $email;

    /**
     * @var string
     * @ORM\Column(type="string", length=150, nullable=true, unique=true)
     */
    public $username;

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
     * @var AppProfile
     * @ORM\ManyToOne(targetEntity="App\Entity\App\AppProfile")
     * @ORM\JoinColumn(nullable=true)
     */
    public $appProfile;

    #> Set and Get
    public function getEmail(): ?string
    {
        return (string) $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt(): string
    {
        return (string) $this->salt;
    }

    public function setSalt(string $salt): self
    {
        $this->salt = $salt;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
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

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @return bool
     */
    public function isFlagAccess(): ?bool
    {
        return $this->flagAccess;
    }

    /**
     * @param bool $flagAccess
     * @return $this
     */
    public function setFlagAccess(bool $flagAccess): self
    {
        $this->flagAccess = $flagAccess;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getIp(): ?string
    {
        return (string) $this->ip;
    }

    /**
     * @param string $ip
     * @return $this
     */
    public function setIp(string $ip): self
    {
        $this->ip = $ip;
        return $this;
    }

    /**
     * @return AppProfile|null
     */
    public function getAppProfile(): ?AppProfile
    {
        return $this->appProfile;
    }

    /**
     * @param AppProfile $appProfile
     * @return $this
     */
    public function setAppProfile(AppProfile $appProfile): self
    {
        $this->appProfile = $appProfile;
        return $this;
    }


}