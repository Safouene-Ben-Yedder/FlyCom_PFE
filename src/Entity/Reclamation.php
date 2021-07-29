<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReclamationRepository")
 */
class Reclamation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_reclamation;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $traite;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Tve", mappedBy="reclamation", cascade={"persist", "remove"})
     */
    private $tve;

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getDateReclamation(): ?\DateTimeInterface
    {
        return $this->date_reclamation;
    }

    public function setDateReclamation(\DateTimeInterface $date_reclamation): self
    {
        $this->date_reclamation = $date_reclamation;

        return $this;
    }

    public function getTraite(): ?bool
    {
        return $this->traite;
    }

    public function setTraite(?bool $traite): self
    {
        $this->traite = $traite;

        return $this;
    }

    public function getTve(): ?Tve
    {
        return $this->tve;
    }

    public function setTve(?Tve $tve): self
    {
        $this->tve = $tve;

        // set (or unset) the owning side of the relation if necessary
        $newReclamation = null === $tve ? null : $this;
        if ($tve->getReclamation() !== $newReclamation) {
            $tve->setReclamation($newReclamation);
        }

        return $this;
    }

    
}
