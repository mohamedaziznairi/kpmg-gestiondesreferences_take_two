<?php

namespace App\Entity;
use App\Repository\WorkstreamsRepository;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Workstreams
 *
 * @ORM\Table(name="workstreams", indexes={@ORM\Index(name="pk_workstream_ref", columns={"referenceId"})})
 * @ORM\Entity
 */
#[ORM\Entity(repositoryClass:WorkstreamsRepository::class)]

class Workstreams
{
    /**
     * @var int
     *
     * @ORM\Column(name="idWorkstream", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(name: 'idWorkstream', type: 'integer', nullable: false)]
    private $idworkstream;

    /**
     * @var string|null
     *
     * @ORM\Column(name="workstream", type="text", length=65535, nullable=true)
     */
    #[ORM\Column(name: 'workstream', type: Types::TEXT, length: 65535, nullable: true)]

    private $workstream;

    /**
     * @var string|null
     *
     * @ORM\Column(name="workstream_VF", type="text", length=65535, nullable=true)
     */
    #[ORM\Column(name: 'workstream_VF', type: Types::TEXT, length: 65535, nullable: true)]

    private $workstreamVf;

    /**
     * @var \Credentials
     *
     * @ORM\ManyToOne(targetEntity="Credentials")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="referenceId", referencedColumnName="referenceId")
     * })
     */
    #[ORM\ManyToOne("App\Entity\Credentials")]
#[ORM\JoinColumn("referenceId", "referenceId")]
    private $referenceid;

    public function getIdworkstream(): ?int
    {
        return $this->idworkstream;
    }

    public function getWorkstream(): ?string
    {
        return $this->workstream;
    }

    public function setWorkstream(?string $workstream): static
    {
        $this->workstream = $workstream;

        return $this;
    }

    public function getWorkstreamVf(): ?string
    {
        return $this->workstreamVf;
    }

    public function setWorkstreamVf(?string $workstreamVf): static
    {
        $this->workstreamVf = $workstreamVf;

        return $this;
    }

    public function getReferenceid(): ?Credentials
    {
        return $this->referenceid;
    }

    public function setReferenceid(?Credentials $referenceid): static
    {
        $this->referenceid = $referenceid;

        return $this;
    }


}
