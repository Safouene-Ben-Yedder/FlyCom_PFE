<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert; 
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EmployeRepository")
 * @UniqueEntity(
 *  fields={"login"},
 *  message="Le non d'utilisateur que vous avez saisi est déja utilisés"
 * )
 *  @UniqueEntity(
 *  fields={"email"},
 *  message="L'email que vous avez saisi est déja utilisés"
 * )
 */
class Employe implements UserInterface
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
    public $cin;

    /**
     * @ORM\Column(type="date")
     */
    public $date_de_naissance;

    /**
     * @ORM\Column(type="string", length=100)
     */
    public $nom_employe;

    /**
     * @ORM\Column(type="integer")
     */
    public $type_employe;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $photo;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    public $login;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\Length(min="8" , minMessage="Votre mot de passe doit faire minimum 8 caractéres")
     * @Assert\EqualTo(propertyPath="confirm_mdp", message="Vous n'avez pas saisi le même mot de passe")
     */
    public $mot_de_passe;

    /**
     * @Assert\EqualTo(propertyPath="mot_de_passe", message="Vous n'avez pas saisi le même mot de passe")
     */ 
    public $confirm_mdp;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Tve", mappedBy="employe")
     */
    public $tves;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $etat;

    /**
     * @var string le token qui servira lors de l'oubli de mot de passe
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $resetToken;

    /**
     * @ORM\Column(type="array")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;


    public function __construct()
    {
        $this->tves = new ArrayCollection();
        $this->notifications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCin(): ?int
    {
        return $this->cin;
    }

    public function setCin(int $cin): self
    {
        $this->cin = $cin;

        return $this;
    }

    public function getDateDeNaissance(): ?\DateTimeInterface
    {
        return $this->date_de_naissance;
    }

    public function setDateDeNaissance(\DateTimeInterface $date_de_naissance): self
    {
        $this->date_de_naissance = $date_de_naissance;

        return $this;
    }

    public function getNomEmploye(): ?string
    {
        return $this->nom_employe;
    }

    public function setNomEmploye(string $nom_employe): self
    {
        $this->nom_employe = $nom_employe;

        return $this;
    }

    public function getTypeEmploye(): ?int
    {
        return $this->type_employe;
    }

    public function setTypeEmploye(int $type_employe): self
    {
        $this->type_employe = $type_employe;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(?string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getMotDePasse(): ?string
    {
        return $this->mot_de_passe;
    }

    public function setMotDePasse(?string $mot_de_passe): self
    {
        $this->mot_de_passe = $mot_de_passe;

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
            $tfe->setEmploye($this);
        }

        return $this;
    }

    public function removeTfe(Tve $tfe): self
    {
        if ($this->tves->contains($tfe)) {
            $this->tves->removeElement($tfe);
            // set the owning side to null (unless already changed)
            if ($tfe->getEmploye() === $this) {
                $tfe->setEmploye(null);
            }
        }

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(?bool $etat): self
    {
        $this->etat = $etat;

        return $this;
    }


    public function getPassword(): ?string
    {
        return $this->mot_de_passe;
    }

    public function getUsername(): ?string
    {
        return $this->login;
    }

    public function eraseCredentials() {}

    public function getSalt() {}

    public function getRoles(){
        if (empty($this->roles)) {
            return ['ROLE_ADMIN'];
        }
        return $this->roles;
    }

    function addRole($role) {
        $this->roles[] = $role;
    }



     /**
     * @return string
     */
    public function getResetToken(): string
    {
        return $this->resetToken;
    }

    /**
     * @param string $resetToken
     */
    public function setResetToken(?string $resetToken): void
    {
        $this->resetToken = $resetToken;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function setConfirmMdp(?string $confirm_mdp): self
    {
        $this->confirm_mdp = $confirm_mdp;

        return $this;
    }
    
}
