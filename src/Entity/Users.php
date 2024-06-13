<?php

namespace App\Entity;
use App\Repository\UsersRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * Users
 *
 * @ORM\Table(name="users")
 * @ORM\Entity
 */
#[ORM\Entity(repositoryClass:UsersRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class Users implements UserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="userId", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(name: 'userId', type: 'integer', nullable: false)]
    private $userid;

  /**
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=250, nullable=false)
     */
    #[ORM\Column(name: 'firstName', type: 'string', length: 250, nullable: false)]

    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=250, nullable=false)
     */
    #[ORM\Column(name: 'lastName', type: 'string', length: 250, nullable: false)]

    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=250, nullable=false)
     */
    #[ORM\Column(name: 'email', type: 'string', length: 250, nullable: false)]

    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=250, nullable=false)
     */
    #[ORM\Column(name: 'password', type: 'string', length: 250, nullable: false)]

    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="profilPhoto", type="blob", length=65535, nullable=false)
     */
    #[ORM\Column(name: 'profilPhoto', type: Types::BLOB, length: 65535, nullable: false)]

    private $profilphoto;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creationDate", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    #[ORM\Column(name: 'creationDate',type: "datetime", nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]

    private $creationdate = 'CURRENT_TIMESTAMP';

    public function getUserid(): ?int
    {
        return $this->userid;
    }
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getProfilphoto()
    {
        return $this->profilphoto;
    }

    public function setProfilphoto($profilphoto): static
    {
        $this->profilphoto = $profilphoto;

        return $this;
    }

    public function getCreationdate(): ?\DateTimeInterface
    {
        return $this->creationdate instanceof \DateTimeInterface ? $this->creationdate : null;
    }
 

    public function setCreationdate(\DateTimeInterface $creationdate): static
    {
        $this->creationdate = $creationdate;

        return $this;
    }

    /*public function __toString()
    {
        return $this->userId ?? 'N/A'; // Return 'N/A' if userId is null
    }*/
    public function __toString()
{
    return (string) $this->firstname; // Convert to string to avoid type errors
}

       /**
         * @see UserInterface
         */
        public function getRoles(): array
        {
            return ['ROLE_USER']; // Assuming all users have the ROLE_USER role

               
        }


    


        /**
         * @see UserInterface
         */
        public function getSalt()
        {
                // not needed when using the "bcrypt" algorithm in security.yaml
        }

        /**
         * @see UserInterface
         */
        public function eraseCredentials()
        {
                // If you store any temporary, sensitive data on the user, clear it here
                // $this->plainPassword = null;
        }

    /**
     * Returns the username used to authenticate the user.
     * @return string
     */
    public function getUsername(): string
    {
        // Assuming that email is used as username
        return $this->email;
    }

    // Symfony 5.3+ UserIdentifier method for compatibility
    public function getUserIdentifier(): string
    {
        return $this->email;
    }
}
