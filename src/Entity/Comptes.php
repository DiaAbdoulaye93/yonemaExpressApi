<?php

namespace App\Entity;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Repository\ComptesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


/**
 * @UniqueEntity(fields={"numero"},message="Veuillez choisir un autre numero de compte celui-ci est exiqte deja")
 * @ApiResource(
 * collectionOperations={
 *  "get"={
      *   "normalization_context"={"groups"={"compte:read"}},
      *    "path"="/caissier/comptes"
      *   },
      * "add_compte"={ 
      *        "denormalization_context"={"groups"={"compte:write"}},
      ** @UniqueEntity
      *  (
      *     fields={"numero"},
      *     message="Ce mumero de compte existe deja"
      *   ),
      *         "method"="POST",
      *         "path"="/admin/comptes",
      *      }
      *    
      *  },
      * itemOperations={
      *   "get"={
      *   "normalization_context"={"groups"={"compte:read"}},
      *   "path"="/caissier/compte/{id}"
      *   },
      *   "update"={
      *   "method"= "put",
      *    "path"= "/caissier/compte/{id}"
      *   },
      *  "delete_compte"=
      *      {
      *         "method"="delete",
      *         "path"="/admin/compte/{id}"
      *      }
      * }
 * )
 * @ORM\Entity(repositoryClass=ComptesRepository::class)
 */
class Comptes
{   
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"user:read", "user:write","agence:read", "agence:write", "compte:read", "compte:write", "transactions:read", "transactions:write"})
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user:read", "user:write","agence:read", "agence:write", "compte:read", "compte:write", "transactions:read", "transactions:write","getConnectedUser"})
     */
    public $numero;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"user:read", "user:write","agence:read", "agence:write", "compte:read", "compte:write", "transactions:read", "transactions:write","getConnectedUser"})
     */
    public $solde;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"agence:read", "agence:write", "compte:read", "compte:write", "transactions:read", "transactions:write"})
     */
    public $createdAt;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"agence:read", "agence:write", "compte:read", "compte:write", "transactions:read", "transactions:write"})
     */
    private $statut;

    /**
     * @ORM\OneToOne(targetEntity=Agence::class, inversedBy="comptes", cascade={"persist", "remove"})
     * * @Groups({"compte:read", "compte:write","transactions:read","commissions:read"})
     */
    public $agence;

    /**
     * @ORM\OneToMany(targetEntity=Transactions::class, mappedBy="compte")
     */
    private $transactions;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): self
    {
        $this->numero = $numero;
        return $this;
    }

    public function getSolde(): ?int
    {
        return $this->solde;
    }

    public function setSolde(int $solde): self
    {
        $this->solde = $solde;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
       
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {

        $this->createdAt = $createdAt;

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
            $transaction->setCompte($this);
        }

        return $this;
    }

    public function removeTransaction(Transactions $transaction): self
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getCompte() === $this) {
                $transaction->setCompte(null);
            }
        }

        return $this;
    }
}
