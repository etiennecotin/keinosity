<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     attributes={"access_control"="is_granted('ROLE_USER')"},
 *     collectionOperations={
 *         "get"={"access_control"="is_granted('ROLE_ADMIN')", "access_control_message"="Sorry, but you are not the book owner lol."},
 *         "post"={"access_control"="is_granted('ROLE_ADMIN')", "access_control_message"="Only admins can add books."}
 *     },
 *     itemOperations={
 *         "get"={"access_control"="is_granted('ROLE_ADMIN')", "access_control_message"="Sorry, but you are not the book owner."}
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("email")
 */
class User implements UserInterface
{

    const INITIAL_ROLE = 'init';
    const ROLE_DEFAULT = 'ROLE_USER';
    const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     * @Groups("project")
     */
    protected $username;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $usernameCanonical;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\Email
     */
    protected $email;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    protected $enabled;

    /**
     * Encrypted password. Must be persisted.
     *
     * @var string
     * @ORM\Column(type="string")
     */
    protected $password;

    /**
     * @var array
     * @ORM\Column(type="array", nullable=true)
     */
    protected $roles;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=300, nullable=true)
     */
    protected $location;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    protected $name;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    protected $firstName;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $birthDay;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true, unique=true)
     */
    protected $token;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $unlockDate;

    /**
     * @var string|null
     * @ORM\Column(type="text", nullable=true)
     */
    protected $lastJwt;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $lastLogin;

    /**
     * @var \DateTime|null
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $createdAt;

    /**
     * @var \DateTime|null
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $updatedAt;

    public function __construct()
    {
        $this->enabled = false;
    }

    public function __toString()
    {
        return (string) $this->email;
    }

    public function create(string $email, string $username, $birthDay, $firstName, $name, $location, $password): User
    {
        $this->email = $email;
        $this->username = $username;
        $this->birthDay =$birthDay;
        $this->firstName = $firstName;
        $this->name = $name;
        $this->location = $location;
        $this->password = $password;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsernameCanonical():? string
    {
        return $this->usernameCanonical;
    }

    /**
     * @param string $usernameCanonical
     * @return User
     */
    public function setUsernameCanonical(string $usernameCanonical): User
    {
        $this->usernameCanonical = $usernameCanonical;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail():? string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail(string $email): User
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnabled():? bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     * @return User
     */
    public function setEnabled(bool $enabled): User
    {
        $this->enabled = $enabled;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getLastLogin(): ?\DateTime
    {
        return $this->lastLogin;
    }

    /**
     * @param \DateTime|null $lastLogin
     * @return User
     */
    public function setLastLogin(?\DateTime $lastLogin): User
    {
        $this->lastLogin = $lastLogin;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->email,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize()
     * @param $serialized
     */
    public function unserialize($serialized): void
    {
        list (
            $this->id,
            $this->email,
            $this->password,
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized, ['allowed_classes' => false]);
    }

    /**
     * @return string|null
     */
    public function getLocation(): ?string
    {
        return $this->location;
    }

    /**
     * @param string|null $location
     */
    public function setLocation(?string $location): void
    {
        $this->location = $location;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string|null $firstName
     */
    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return \DateTime|null
     */
    public function getBirthDay(): ?\DateTime
    {
        return $this->birthDay;
    }

    /**
     * @param \DateTime|null $birthDay
     */
    public function setBirthDay(?\DateTime $birthDay): void
    {
        $this->birthDay = $birthDay;
    }

    /**
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @param string|null $token
     */
    public function setToken(?string $token): void
    {
        $this->token = $token;
    }

    /**
     * @return bool|null
     */
    public function getEnable(): ?bool
    {
        return $this->enable;
    }

    /**
     * @param bool|null $enable
     */
    public function setEnable(?bool $enable): void
    {
        $this->enable = $enable;
    }

    /**
     * @return \DateTime|null
     */
    public function getUnlockDate(): ?\DateTime
    {
        return $this->unlockDate;
    }

    /**
     * @param \DateTime|null $unlockDate
     */
    public function setUnlockDate(?\DateTime $unlockDate): void
    {
        $this->unlockDate = $unlockDate;
    }


    /**
     * @return array|null
     */
    public function getRoles() :? array
    {
        if (empty($this->roles)) {
            $this->roles[] = 'ROLE_USER';
        }
        return $this->roles;
    }

    /**
     * {@inheritdoc}
     */
    public function hasRole($role)
    {
        return in_array(strtoupper($role), $this->getRoles(), true);
    }

    public function isSuperAdmin()
    {
        return $this->hasRole(static::ROLE_SUPER_ADMIN);
    }

    /**
     * {@inheritdoc}
     */
    public function removeRole($role)
    {
        if (false !== $key = array_search(strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setSuperAdmin($boolean)
    {
        if (true === $boolean) {
            $this->addRole(static::ROLE_SUPER_ADMIN);
        } else {
            $this->removeRole(static::ROLE_SUPER_ADMIN);
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setRoles(array $roles)
    {
        $this->roles = array();
        foreach ($roles as $key=>$role) {
            if ($key !== static::INITIAL_ROLE) {
                $this->addRole($role);
            } else {
                $this->roles[] = $role;
            }
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeAllRoles()
    {
        $this->roles = null;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addRole($role)
    {
        $role = strtoupper($role);
        if ($role === static::ROLE_DEFAULT) {
            return $this;
        }
        if (is_array($this->roles)){
            if (!in_array($role, $this->roles, true)) {
                $this->roles[] = $role;
            }
        }else{
            $this->roles[] = $role;
        }
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime|null $createdAt
     */
    public function setCreatedAt(?\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime|null
     */
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime|null $updatedAt
     */
    public function setUpdatedAt(?\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return string|null
     */
    public function getLastJwt(): ?string
    {
        return $this->lastJwt;
    }

    /**
     * @param string|null $lastJwt
     */
    public function setLastJwt(?string $lastJwt): void
    {
        $this->lastJwt = $lastJwt;
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function getPassword()
    {
        return (string) $this->password;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return (string) $this->username;
    }

    /**
     * @param string $username
     * @return User
     */
    public function setUsername(string $username): User
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword(string $password): User
    {
        $this->password = $password;
        return $this;
    }
}
