<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TveRepository")
 */
class Tve
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Vol", inversedBy="tves")
     * @ORM\JoinColumn(nullable=false)
     */
    public $vol;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Employe", inversedBy="tves")
     * @ORM\JoinColumn(nullable=false)
     */
    public $employe;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tache", inversedBy="tves")
     * @ORM\JoinColumn(nullable=false)
     */
    public $tache;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $date_debut_tache;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    public $date_fin_tache;

    
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $essai;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Reclamation", inversedBy="tve", cascade={"persist", "remove"})
     */
    private $reclamation;

    

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVol(): ?Vol
    {
        return $this->vol;
    }

    public function setVol(?Vol $vol): self
    {
        $this->vol = $vol;

        return $this;
    }

    public function getEmploye(): ?Employe
    {
        return $this->employe;
    }

    public function setEmploye(?Employe $employe): self
    {
        $this->employe = $employe;

        return $this;
    }

    public function getTache(): ?Tache
    {
        return $this->tache;
    }

    public function setTache(?Tache $tache): self
    {
        $this->tache = $tache;

        return $this;
    }

    public function getDateDebutTache(): ?\DateTimeInterface
    {
        return $this->date_debut_tache;
    }

    public function setDateDebutTache(?\DateTimeInterface $date_debut_tache): self
    {
        $this->date_debut_tache = $date_debut_tache;

        return $this;
    }

    public function getDateFinTache(): ?\DateTimeInterface
    {
        return $this->date_fin_tache;
    }

    public function setDateFinTache(?\DateTimeInterface $date_fin_tache): self
    {
        $this->date_fin_tache = $date_fin_tache;

        return $this;
    }

    

    public function getEssai(): ?int
    {
        return $this->essai;
    }

    public function setEssai(?int $essai): self
    {
        $this->essai = $essai;

        return $this;
    }

    public function getReclamation(): ?Reclamation
    {
        return $this->reclamation;
    }

    public function setReclamation(?Reclamation $reclamation): self
    {
        $this->reclamation = $reclamation;

        return $this;
    }

   
    

}
