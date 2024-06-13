<?php

namespace App\Entity;
use App\Repository\ClientsRepository;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Clients
 *
 * @ORM\Table(name="clients")
 * @ORM\Entity
 */
#[ORM\Entity(repositoryClass:ClientsRepository::class)]

class Clients
{
    /**
     * @var int
     *
     * @ORM\Column(name="idClient", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(name: 'idClient', type: 'integer', nullable: false)]
    private $idclient;

    /**
     * @var string
     *
     * @ORM\Column(name="companyName", type="string", length=250, nullable=false)
     */
    #[ORM\Column(name: 'companyName', type: 'string', length: 250, nullable: false)]

    private $companyname;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="addedDate", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    #[ORM\Column(name: 'addedDate', type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    

    private $addeddate = 'CURRENT_TIMESTAMP';

    public function getIdclient(): ?int
    {
        return $this->idclient;
    }

    public function getCompanyname(): ?string
    {
        return $this->companyname;
    }

    public function setCompanyname(string $companyname): static
    {
        $this->companyname = $companyname;

        return $this;
    }

    public function getAddeddate(): ?\DateTimeInterface
    {
        return $this->addeddate instanceof \DateTimeInterface ? $this->addeddate : null;
    }

    public function setAddeddate(\DateTimeInterface $addeddate): static
    {
        $this->addeddate = $addeddate;
        

        return $this;
    }

    public function __toString()
    {
        return $this->idclient ?? 'N/A'; // Return 'N/A' if userId is null
    }
}
