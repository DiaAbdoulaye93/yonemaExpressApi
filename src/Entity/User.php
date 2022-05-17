<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 *  denormalizationContext={"groups"={"user:write"}}
 *  @UniqueEntity(fields={"telephone"},message="Veuillez choisir un autre numero de telephone")
 *   @ApiResource(
 *  denormalizationContext={"groups"={"user:write"}},
 *  normalizationContext = {"groups" = {"user:read"}},
 * collectionOperations={
 *   "get"={
 *   "path"="/admin/users"
 * },
 *   "getConnectedUser"={
 *     "method"="get",
 *     "route_name" = "getConnectedUser",
 * 
 * },
 *   "add_users"=
 *     {
 *       
 *         "method"="POST",
 *         "path"="/admin/users",
 *      }
 *    
 *  },
 * itemOperations={
 *   "get"={
 *   "normalization_context"={"groups"={"user:read"}},
 *   "path"="/admin/user/{id}"
 *   },
 *   "edit_user"={
 *   "method"= "put",
 *   "path"= "/admin/users/{id}"
 *   },
 *  "delete_user"=
 *      {
 *         "method"="delete",
 *         "path"="/admin/user/{id}"
 *      }
 * }
 *)
 *   
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *  @Groups({"user:read", "user:write","agence:read", "agence:write","transactions:read", "transactions:write","getConnectedUser"})
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     *  @Groups({"user:read", "user:write","agence:read", "agence:write"})
     */
    public $email;

    /**
     * @ORM\Column(type="json")
     *  @Groups({"user:read", "user:write","agence:read", "agence:write","getConnectedUser"})
     */
    public $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Groups({"user:read", "user:write","agence:read", "agence:write"})
     */
    public $password;

    /**
     *  @ORM\Column(type="string", length=255)
     *  @Groups({"user:read", "user:write","agence:read", "agence:write" ,"getConnectedUser"})
     */
    public $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user:read", "user:write" ,"agence:read", "agence:write","getConnectedUser"})
     */
    public $prenom;

    /**
     * @ORM\Column(type="integer")
     *  @Groups({"agence:read", "agence:write", "user:read", "user:write", "transactions:read","getConnectedUser"})
     */
    public $telephone;

    /**
     * @ORM\ManyToOne(targetEntity=Profile::class, inversedBy="users", cascade={"persist", "remove"})
     * @Groups({"user:write", "user:read","agence:read", "agence:write", "getConnectedUser"})
     */
    public $profil;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $statut = false;

    /**
     * @ORM\ManyToOne(targetEntity=Agence::class, inversedBy="users", cascade={"persist", "remove"})
     * @Groups({"user:write","user:read", "getConnectedUser"})
     */
    public $agence;

    /**
     * @ORM\OneToMany(targetEntity=Transactions::class, mappedBy="user")
     */
    public $transactions;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): int
    {
        return (int) $this->telephone;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
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

    public function getProfil(): ?Profile
    {
        return $this->profil;
    }

    public function setProfil(?Profile $profil): self
    {
        $this->profil = $profil;

        return $this;
    }

    public function getStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(?bool $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getAgence(): ?Agence
    {
        return $this->agence;
    }

    public function setAgence(?Agence $agence): self
    {
        $this->agence = $agence;

        return $this;
    }

    /**
     * @return Collection|Transactions[]
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transactions $transaction): self
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions[] = $transaction;
            $transaction->setUser($this);
        }

        return $this;
    }

    public function removeTransaction(Transactions $transaction): self
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getUser() === $this) {
                $transaction->setUser(null);
            }
        }

        return $this;
    }
}
