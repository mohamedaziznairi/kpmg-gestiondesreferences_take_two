<?php

namespace App\Entity;
use App\Repository\CredentialsRepository;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Credentials
 *
 * @ORM\Table(name="credentials", indexes={@ORM\Index(name="fk_user_ref", columns={"userId"}), @ORM\Index(name="fk_client_cred", columns={"client"})})
 * @ORM\Entity
 */
#[ORM\Entity(repositoryClass:CredentialsRepository::class)]

class Credentials
{
    /**
     * @var int
     *
     * @ORM\Column(name="referenceId", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(name: 'referenceId', type: 'integer', nullable: false)]
    private $referenceid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="country", type="string", length=250, nullable=true)
     */
    #[ORM\Column(name: 'country', type: 'string', length: 250, nullable: true)]

    private $country;

    /**
     * @var string|null
     *
     * @ORM\Column(name="projectTitle", type="string", length=250, nullable=true)
     */
    #[ORM\Column(name: 'projectTitle', type: 'string', length: 250, nullable: true)]

    private $projecttitle;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=true)
     */
    #[ORM\Column(name: 'description', type: Types::TEXT, nullable: true)]

    private $description;

    /**
     * @var string|null
     *
     * @ORM\Column(name="country_VF", type="string", length=250, nullable=true)
     */
    #[ORM\Column(name: 'country_VF', type: 'string', length: 250, nullable: true)]

    private $countryVf;

    /**
     * @var string|null
     *
     * @ORM\Column(name="projectTitle_VF", type="string", length=250, nullable=true)
     */
    #[ORM\Column(name: 'projectTitle_VF', type: 'string', length: 250, nullable: true)]

    private $projecttitleVf;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description_VF", type="text", length=65535, nullable=true)
     */
    #[ORM\Column(name: 'description_VF', type: Types::TEXT, nullable: true)]

    private $descriptionVf;
    /**
     * @var \Clients
     *
     * @ORM\ManyToOne(targetEntity="Clients")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="client", referencedColumnName="idClient")
     * })
     */
    #[ORM\ManyToOne("App\Entity\Clients")]
    #[ORM\JoinColumn("client", "idClient")]
    private $client;

    /**
     * @var \Users
     *
     * @ORM\ManyToOne(targetEntity="Users")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="userId", referencedColumnName="userId")
     * })
     * 
     */
    #[ORM\ManyToOne("App\Entity\Users")]
    #[ORM\JoinColumn("userId", "userId")]
    
    private $userid;

    public function getReferenceid(): ?int
    {
        return $this->referenceid;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getProjecttitle(): ?string
    {
        return $this->projecttitle;
    }

    public function setProjecttitle(?string $projecttitle): static
    {
        $this->projecttitle = $projecttitle;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCountryVf(): ?string
    {
        return $this->countryVf;
    }

    public function setCountryVf(?string $countryVf): static
    {
        $this->countryVf = $countryVf;

        return $this;
    }

    public function getProjecttitleVf(): ?string
    {
        return $this->projecttitleVf;
    }

    public function setProjecttitleVf(?string $projecttitleVf): static
    {
        $this->projecttitleVf = $projecttitleVf;

        return $this;
    }

    public function getDescriptionVf(): ?string
    {
        return $this->descriptionVf;
    }

    public function setDescriptionVf(?string $descriptionVf): static
    {
        $this->descriptionVf = $descriptionVf;

        return $this;
    }

    public function getClient(): ?Clients
    {
        return $this->client;
    }

    public function setClient(?Clients $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getUserid(): ?Users
    {
        return $this->userid;
    }

    public function setUserid(?Users $userid): static
    {
        $this->userid = $userid;

        return $this;
    }
    public function __toString()
    {
        return $this->referenceid ?? 'N/A'; // Return 'N/A' if userId is null
    }

}
