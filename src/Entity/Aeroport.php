<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AeroportRepository")
 * @UniqueEntity(
 *  fields={"nom_aeroport"},
 *  message="Cet avion existe déja"
 * ) 
 *@UniqueEntity(
 *  fields={"code_aeroport"},
 *  message="Cet avion existe déja"
 * )
 */
class Aeroport
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
    public $nom_aeroport;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Vol", mappedBy="aeroport_arrivee")
     */
    public $vols_arrivee;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Vol", mappedBy="aeroport_depart")
     */
    public $vols_depart;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Vol", mappedBy="aeroports_escales")
     */
    private $vols_escales;

    /**
     * @ORM\Column(type="integer")
     */
    private $code_aeroport;

    

    public function __construct()
    {
        $this->vols_arrivee = new ArrayCollection();
        $this->vols_depart = new ArrayCollection();
        $this->vols_escales = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomAeroport(): ?string
    {
        return $this->nom_aeroport;
    }

    public function setNomAeroport(string $nom_aeroport): self
    {
        $this->nom_aeroport = $nom_aeroport;

        return $this;
    }

    /**
     * @return Collection|Vol[]
     */
    public function getVolsArrivee(): Collection
    {
        return $this->vols_arrivee;
    }

    public function addVolsArrivee(Vol $volsArrivee): self
    {
        if (!$this->vols_arrivee->contains($volsArrivee)) {
            $this->vols_arrivee[] = $volsArrivee;
            $volsArrivee->setAeroportArrivee($this);
        }

        return $this;
    }

    public function removeVolsArrivee(Vol $volsArrivee): self
    {
        if ($this->vols_arrivee->contains($volsArrivee)) {
            $this->vols_arrivee->removeElement($volsArrivee);
            // set the owning side to null (unless already changed)
            if ($volsArrivee->getAeroportArrivee() === $this) {
                $volsArrivee->setAeroportArrivee(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Vol[]
     */
    public function getVolsDepart(): Collection
    {
        return $this->vols_depart;
    }

    public function addVolsDepart(Vol $volsDepart): self
    {
        if (!$this->vols_depart->contains($volsDepart)) {
            $this->vols_depart[] = $volsDepart;
            $volsDepart->setAeroportDepart($this);
        }

        return $this;
    }

    public function removeVolsDepart(Vol $volsDepart): self
    {
        if ($this->vols_depart->contains($volsDepart)) {
            $this->vols_depart->removeElement($volsDepart);
            // set the owning side to null (unless already changed)
            if ($volsDepart->getAeroportDepart() === $this) {
                $volsDepart->setAeroportDepart(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Vol[]
     */
    public function getVolsEscales(): Collection
    {
        return $this->vols_escales;
    }

    public function addVolsEscale(Vol $volsEscale): self
    {
        if (!$this->vols_escales->contains($volsEscale)) {
            $this->vols_escales[] = $volsEscale;
            $volsEscale->addAeroportsEscale($this);
        }

        return $this;
    }

    public function removeVolsEscale(Vol $volsEscale): self
    {
        if ($this->vols_escales->contains($volsEscale)) {
            $this->vols_escales->removeElement($volsEscale);
            $volsEscale->removeAeroportsEscale($this);
        }

        return $this;
    }

    public function getCodeAeroport(): ?int
    {
        return $this->code_aeroport;
    }

    public function setCodeAeroport(int $code_aeroport): self
    {
        $this->code_aeroport = $code_aeroport;

        return $this;
    }

    
}
