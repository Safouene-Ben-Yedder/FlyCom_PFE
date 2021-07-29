<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategorieAvionRepository")
 */
class CategorieAvion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    

    /**
     * @ORM\Column(type="integer")
     */
    private $capacite;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Avion", mappedBy="categorie")
     */
    private $avions;

    /**
     * @ORM\Column(type="integer")
     */
    private $code_categorie;

    

    
    public function __construct()
    {
        $this->avions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    

    public function getCapacite(): ?int
    {
        return $this->capacite;
    }

    public function setCapacite(int $capacite): self
    {
        $this->capacite = $capacite;

        return $this;
    }

    /**
     * @return Collection|Avion[]
     */
    public function getAvions(): Collection
    {
        return $this->avions;
    }

    public function addAvion(Avion $avion): self
    {
        if (!$this->avions->contains($avion)) {
            $this->avions[] = $avion;
            $avion->setCategorie($this);
        }

        return $this;
    }

    public function removeAvion(Avion $avion): self
    {
        if ($this->avions->contains($avion)) {
            $this->avions->removeElement($avion);
            // set the owning side to null (unless already changed)
            if ($avion->getCategorie() === $this) {
                $avion->setCategorie(null);
            }
        }

        return $this;
    }

    public function getCodeCategorie(): ?int
    {
        return $this->code_categorie;
    }

    public function setCodeCategorie(int $code_categorie): self
    {
        $this->code_categorie = $code_categorie;

        return $this;
    }

    
   

    
}

