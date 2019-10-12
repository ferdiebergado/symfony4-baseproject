<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use function array_unique;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     *
     * @var string
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     *
     * @var array
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @ORM\Column(type="json")
     * @ORM\Column(type="string")
     *
     * @var string The hashed password
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string
     */
    private $verificationToken;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var DateTime
     */
    private $verifiedAt;

    /** @ORM\Column(type="boolean", nullable=true)
     *
     * @var bool */
    private $isActive = false;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var DateTime $created
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var DateTime $updated
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id")
     *
     * @var User $createdBy
     */
    private $createdBy;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="updated_by", referencedColumnName="id")
     *
     * @var User $updatedBy
     */
    private $updatedBy;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

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
    public function getSalt(): void
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getVerificationToken(): ?string
    {
        return $this->verificationToken;
    }

    public function setVerificationToken(?string $verificationToken): self
    {
        $this->verificationToken = $verificationToken;

        return $this;
    }

    public function getVerifiedAt(): ?DateTimeInterface
    {
        return $this->verifiedAt;
    }

    public function setVerifiedAt(?DateTimeInterface $verifiedAt): self
    {
        $this->verifiedAt = $verifiedAt;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Set the createdAt field to the current timestamp.
     *
     * @ORM\PrePersist
     */
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new DateTime();
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * Set the updatedAt field to the current timestamp.
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new DateTime();
    }

    public function getCreatedBy(): ?self
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?self $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getUpdatedBy(): ?self
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(?self $updatedBy): self
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }
}
