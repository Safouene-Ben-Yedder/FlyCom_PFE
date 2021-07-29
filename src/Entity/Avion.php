<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AvionRepository")
 * @UniqueEntity(
 *  fields={"code_avion"},
 *  message="Cet avion existe déja"
 * )
 * @UniqueEntity(
 *  fields={"nom_avion"},
 *  message="Cet avion existe déja"
 * )
 */
class Avion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    public $code_avion;


     /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom_avion;


   

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Vol", mappedBy="avion")
     */
    public $vols;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CategorieAvion", inversedBy="avions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categorie;

    

    
    
    public function __construct()
    {
        $this->vols = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeAvion(): ?string
    {
        return $this->code_avion;
    }

    public function setCodeAvion(string $code_avion): self
    {
        $this->code_avion = $code_avion;

        return $this;
    }


    /**
     * @return Collection|Vol[]
     */
    public function getVols(): Collection
    {
        return $this->vols;
    }

    public function addVol(Vol $vol): self
    {
        if (!$this->vols->contains($vol)) {
            $this->vols[] = $vol;
            $vol->setAvion($this);
        }

        return $this;
    }

    public function removeVol(Vol $vol): self
    {
        if ($this->vols->contains($vol)) {
            $this->vols->removeElement($vol);
            // set the owning side to null (unless already changed)
            if ($vol->getAvion() === $this) {
                $vol->setAvion(null);
            }
        }

        return $this;
    }

    public function getNomAvion(): ?string
    {
        return $this->nom_avion;
    }

    public function setNomAvion(string $nom_avion): self
    {
        $this->nom_avion = $nom_avion;

        return $this;
    }

  
    public function getCategorie(): ?CategorieAvion
    {
        return $this->categorie;
    }

    public function setCategorie(?CategorieAvion $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

   

   
}
