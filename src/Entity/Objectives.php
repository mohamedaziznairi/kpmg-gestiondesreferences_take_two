<?php

namespace App\Entity;
use App\Repository\ObjectivesRepository;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Objectives
 *
 * @ORM\Table(name="objectives", indexes={@ORM\Index(name="fk_obj_ref", columns={"referenceId"})})
 * @ORM\Entity
 */
#[ORM\Entity(repositoryClass:ObjectivesRepository::class)]

class Objectives
{
    /**
     * @var int
     *
     * @ORM\Column(name="idObjectif", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(name: 'idObjectif', type: 'integer', nullable: false)]
    private $idobjectif;

    /**
     * @var string|null
     *
     * @ORM\Column(name="objectif", type="text", length=65535, nullable=true)
     */
    #[ORM\Column(name: 'objectif', type: Types::TEXT, nullable: true)]

    private $objectif;

    /**
     * @var string|null
     *
     * @ORM\Column(name="objectif_VF", type="text", length=65535, nullable=true)
     */
    #[ORM\Column(name: 'objectif_VF', type: Types::TEXT, nullable: true)]

    private $objectifVf;

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

    public function getIdobjectif(): ?int
    {
        return $this->idobjectif;
    }

    public function getObjectif(): ?string
    {
        return $this->objectif;
    }

    public function setObjectif(?string $objectif): static
    {
        $this->objectif = $objectif;

        return $this;
    }

    public function getObjectifVf(): ?string
    {
        return $this->objectifVf;
    }

    public function setObjectifVf(?string $objectifVf): static
    {
        $this->objectifVf = $objectifVf;

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
