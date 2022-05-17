<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AgenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 * collectionOperations={
      *    "get"={
      *   "normalization_context"={"groups"={"agence:read"}},
      *   "path"="/admin/agences"
      *   },
      *   "add_agence"=
      *     { "denormalization_context"={"groups"={"agence:write"}},
      *         "method"="POST",
      *         "path"="/admin/agences",
      *      }
      *    
      *  },
      * itemOperations={
      *   "get"={
      *   "normalization_context"={"groups"={"agence:read"}},
      *   "path"="/admin/agences/{id}"
      *   },
      *   "edit_user"={
      *   "method"= "put",
             *"path"= "/admin/agences/{id}"
      *   },
      *  "delete_user"=
      *      {
      *         "method"="delete",
      *         "path"="/admin/agences/{id}"
      *      }
      * }
      *)
 * @ORM\Entity(repositoryClass=AgenceRepository::class)
 */
class Agence
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({ "user:read", "user:write","agence:read", "agence:write", "compte:read", "compte:write", "transactions:read","commissions:read"})
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=255)
     *@Groups({"user:read", "user:write","agence:read", "agence:write", "compte:read", "compte:write","transactions:read","getConnectedUser"})
     */
    private $nom;


    /**
     * @ORM\Column(type="integer")
     * @Groups({"user:read", "user:write","agence:read", "agence:write", "compte:read", "compte:write"})
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255)
     *@Groups({"user:read", "user:write","agence:read", "agence:write", "compte:read", "compte:write"})
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)
     *@Groups({"agence:read", "agence:write", "compte:read", "compte:write"})
     */
    private $latitude;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"agence:read", "agence:write"})
     */
    private $longitude;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="agence", cascade={"persist", "remove"})
     * @Groups({"agence:read", "agence:write"})
     */
    public $users;

    /**
     * @ORM\OneToOne(targetEntity=Comptes::class, mappedBy="agence", cascade={"persist", "remove"})
     * @Groups({"user:read", "user:write","agence:read", "agence:write","getConnectedUser"})
     */
    public $comptes;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTelephone(): ?int
    {
      
        return $this->telephone;
    }

    public function setTelephone(int $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setAgence($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getAgence() === $this) {
                $user->setAgence(null);
            }
        }

        return $this;
    }

    public function getComptes(): ?Comptes
    {
        return $this->comptes;
    }

    public function setComptes(?Comptes $comptes): self
    {
        // unset the owning side of the relation if necessary
        if ($comptes === null && $this->comptes !== null) {
            $this->comptes->setAgence(null);
        }

        // set the owning side of the relation if necessary
        if ($comptes !== null && $comptes->getAgence() !== $this) {
            $comptes->setAgence($this);
        }

        $this->comptes = $comptes;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }
}
