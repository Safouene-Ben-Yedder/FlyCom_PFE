<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass="App\Repository\VolRepository")
 * @UniqueEntity(
 *  fields={"num_vol"},
 *  message="Ce vol existe "
 * )
 */
class Vol
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="integer")
     */
    public $num_vol;
 
   
    /**
     * @ORM\Column(type="datetime")
     */
    public $date_arrivee;

    /**
     * @ORM\Column(type="datetime")
     */
    public $date_depart;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Aeroport", inversedBy="vols_arrivee")
     * @ORM\JoinColumn(nullable=false)
     */
    public $aeroport_arrivee;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Aeroport", inversedBy="vols_depart")
     * @ORM\JoinColumn(nullable=false)
     */
    public $aeroport_depart;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Tve", mappedBy="vol", cascade={"all"})
     */
    public $tves;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Avion", inversedBy="vols")
     * @ORM\JoinColumn(nullable=false)
     */
    public $avion;


    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $retard;

   
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Aeroport", inversedBy="vols_escales")
     */
    private $aeroports_escales;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Intervention", inversedBy="vol", cascade={"persist", "remove"})
     */
    private $intervention;

   
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $retard_prediction;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public $taches_commencees;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public $taches_terminees;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Notification", mappedBy="vol")
     */
    private $notifications;



    public function __construct()
    {
        $this->tves = new ArrayCollection();
        $this->aeroports_escales = new ArrayCollection();
        $this->notifications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumVol(): ?int
    {
        return $this->num_vol;
    }

    public function setNumVol(int $num_vol): self
    {
        $this->num_vol = $num_vol;

        return $this;
    }

    

    public function getDateArrivee(): ?\DateTimeInterface
    {
        return $this->date_arrivee;
    }

    public function setDateArrivee(\DateTimeInterface $date_arrivee): self
    {
        $this->date_arrivee = $date_arrivee;

        return $this;
    }

    public function getDateDepart(): ?\DateTimeInterface
    {
        return $this->date_depart;
    }

    public function setDateDepart(\DateTimeInterface $date_depart): self
    {
        $this->date_depart = $date_depart;

        return $this;
    }

    public function getAeroportArrivee(): ?Aeroport
    {
        return $this->aeroport_arrivee;
    }

    public function setAeroportArrivee(?Aeroport $aeroport_arrivee): self
    {
        $this->aeroport_arrivee = $aeroport_arrivee;

        return $this;
    }

    public function getAeroportDepart(): ?Aeroport
    {
        return $this->aeroport_depart;
    }

    public function setAeroportDepart(?Aeroport $aeroport_depart): self
    {
        $this->aeroport_depart = $aeroport_depart;

        return $this;
    }

    /**
     * @return Collection|Tve[]
     */
    public function getTves(): Collection
    {
        return $this->tves;
    }

    public function addTve(Tve $tfe): self
    {
        if (!$this->tves->contains($tfe)) {
            $this->tves[] = $tfe;
            $tfe->setVol($this);
        }

        return $this;
    }

    public function removeTve(Tve $tfe): self
    {
        if ($this->tves->contains($tfe)) {
            $this->tves->removeElement($tfe);
            // set the owning side to null (unless already changed)
            if ($tfe->getVol() === $this) {
                $tfe->setVol(null);
            }
        }

        return $this;
    }

    public function getAvion(): ?Avion
    {
        return $this->avion;
    }

    public function setAvion(?Avion $avion): self
    {
        $this->avion = $avion;

        return $this;
    }

    

    public function getRetard(): ?bool
    {
        return $this->retard;
    }

    public function setRetard(?bool $retard): self
    {
        $this->retard = $retard;

        return $this;
    }


    /**
     * @return Collection|Aeroport[]
     */
    public function getAeroportsEscales(): Collection
    {
        return $this->aeroports_escales;
    }

    public function addAeroportsEscale(Aeroport $aeroportsEscale): self
    {
        if (!$this->aeroports_escales->contains($aeroportsEscale)) {
            $this->aeroports_escales[] = $aeroportsEscale;
        }

        return $this;
    }

    public function removeAeroportsEscale(Aeroport $aeroportsEscale): self
    {
        if ($this->aeroports_escales->contains($aeroportsEscale)) {
            $this->aeroports_escales->removeElement($aeroportsEscale);
        }

        return $this;
    }

    public function getIntervention(): ?Intervention
    {
        return $this->intervention;
    }

    public function setIntervention(?Intervention $intervention): self
    {
        $this->intervention = $intervention;

        return $this;
    }


    public function getRetardPrediction(): ?bool
    {
        return $this->retard_prediction;
    }

    public function setRetardPrediction(?bool $retard_prediction): self
    {
        $this->retard_prediction = $retard_prediction;

        return $this;
    }

    public function getTachesCommencees(): ?int
    {
        return $this->taches_commencees;
    }

    public function setTachesCommencees(?int $taches_commencees): self
    {
        $this->taches_commencees = $taches_commencees;

        return $this;
    }

    public function getTachesTerminees(): ?int
    {
        return $this->taches_terminees;
    }

    public function setTachesTerminees(?int $taches_terminees): self
    {
        $this->taches_terminees = $taches_terminees;

        return $this;
    }

    /**
     * @return Collection|Notification[]
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications[] = $notification;
            $notification->setVol($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        if ($this->notifications->contains($notification)) {
            $this->notifications->removeElement($notification);
            // set the owning side to null (unless already changed)
            if ($notification->getVol() === $this) {
                $notification->setVol(null);
            }
        }

        return $this;
    }


    
}
