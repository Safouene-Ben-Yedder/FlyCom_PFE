<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TacheRepository")
 */
class Tache
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Tve", mappedBy="tache")
     */
    public $tves;

   

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code_tache;

    
    public function __construct()
    {
        $this->tves = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Tve[]
     */
    public function getTves(): Collection
    {
        return $this->tves;
    }

    public function addTfe(Tve $tfe): self
    {
        if (!$this->tves->contains($tfe)) {
            $this->tves[] = $tfe;
            $tfe->setTache($this);
        }

        return $this;
    }

    public function removeTfe(Tve $tfe): self
    {
        if ($this->tves->contains($tfe)) {
            $this->tves->removeElement($tfe);
            // set the owning side to null (unless already changed)
            if ($tfe->getTache() === $this) {
                $tfe->setTache(null);
            }
        }

        return $this;
    }

    public function getDureeMax(): ?\DateTimeInterface
    {
        return $this->duree_max;
    }

    public function setDureeMax(\DateTimeInterface $duree_max): self
    {
        $this->duree_max = $duree_max;

        return $this;
    }

    public function getCodeTache(): ?string
    {
        return $this->code_tache;
    }

    public function setCodeTache(string $code_tache): self
    {
        $this->code_tache = $code_tache;

        return $this;
    }

  
}
