<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TransactionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
/**
 * @ApiResource(
 * collectionOperations={
 *  "get"={
      *   "normalization_context"={"groups"={"transactions:read"}},
      *    "path"="/admin/transactions"
      *   },
      * "make_transaction"={ 
      *        "denormalization_context"={"groups"={"transactions:write"}},
      *         "method"="POST",
      *         "path"="/transactions",
      *         "normalization_context"={"groups"={"transactions:read"}},
      *      }
      *    
      *  },
      * itemOperations={
      *   "get"={
      *   "normalization_context"={"groups"={"transactions:read"}},
      *   "path"="/transactions/{id}"
      *   },
      *   "retrait"={
      *   "method"= "put",
      *    "path"= "/transactions"
      *   },
      *  "remov_transaction"=
      *      {
      *         "method"="delete",
      *         "path"="/admin/transactions/{id}"
      *      }
      * }
      *)
 * @ORM\Entity(repositoryClass=TransactionsRepository::class)
 */
class Transactions
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"transactions:read", "transactions:write", "commissions:read"})
     */
    public $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"transactions:read", "transactions:write"})
     */
    public $montant;

    /**
     * @ORM\Column(type="date")
     * @Groups({"transactions:read", "transactions:write"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"transactions:read", "transactions:write"})
     */
    public $date_retrait;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"transactions:read", "transactions:write"})
     */
    public $code;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"transactions:read", "transactions:write"})
     */
    public $frais;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="transactions",  cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     *  @Groups({"transactions:read", "transactions:write"})
     */
    private $user;
  
    /**
     * @ORM\ManyToOne(targetEntity=Comptes::class, inversedBy="transactions",  cascade={"persist", "remove"})
     * @Groups({"transactions:read", "transactions:write","commissions:read"})
     */

    public $compte;
    /**
     * @ORM\Column(type="integer")
     * @Groups({"transactions:read", "transactions:write"})
     */
    public $telephone;

    /**
     * @ORM\Column(type="bigint", length=20) 
     * @Groups({"transactions:read", "transactions:write"})
     */
    private $cni;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"transactions:read", "transactions:write"})
     */
    private $nomemet;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"transactions:read", "transactions:write"})
     */
    private $prenomemet;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"transactions:read", "transactions:write"})
     */
    private $nombenef;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"transactions:read", "transactions:write"})
     */
    private $prenombenef;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"transactions:read", "transactions:write"})
     */
    public $telephonebenef;

    /**
     * @ORM\Column(type="integer")
     *  @Groups({"transactions:read", "transactions:write"})
     */
    public $total;

    /**
     * @ORM\OneToMany(targetEntity=Commissions::class, mappedBy="transaction", cascade={"persist", "remove"})
     * @Groups({"transactions:read", "transactions:write"})
     */
    public $commissions;
    /**
     * @ORM\ManyToOne(targetEntity=Comptes::class)
     * @Groups({"transactions:read", "transactions:write"})
     */
    public $comptederetrait;

    /**
     * @ORM\Column(type="bigint")
     * @Groups({"transactions:read", "transactions:write"})
     */
    private $cnireceveur;

    
    public function __construct()
    {
        $this->type = new ArrayCollection();
        $this->commissions = new ArrayCollection();
    }

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): self
    {
        $this->montant = $montant;

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

    public function getDateRetrait(): ?\DateTimeInterface
    {
        return $this->date_retrait;
    }

    public function setDateRetrait(?\DateTimeInterface $date_retrait): self
    {
        $this->date_retrait = $date_retrait;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getFrais(): ?int
    {
        return $this->frais;
    }

    public function setFrais(int $frais): self
    {
        $this->frais = $frais;

        return $this;
    }

    

    public function getCompte(): ?Comptes
    {
        return $this->compte;
    }

    public function setCompte(?Comptes $compte): self
    {
        $this->compte = $compte;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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

    public function getCni(): ?int
    {
        return $this->cni;
    }

    public function setCni(int $cni): self
    {
        $this->cni = $cni;

        return $this;
    }

    public function getNomemet(): ?string
    {
        return $this->nomemet;
    }

    public function setNomemet(string $nomemet): self
    {
        $this->nomemet = $nomemet;

        return $this;
    }

    public function getPrenomemet(): ?string
    {
        return $this->prenomemet;
    }

    public function setPrenomemet(string $prenomemet): self
    {
        $this->prenomemet = $prenomemet;

        return $this;
    }

    public function getNombenef(): ?string
    {
        return $this->nombenef;
    }

    public function setNombenef(string $nombenef): self
    {
        $this->nombenef = $nombenef;

        return $this;
    }

    public function getPrenombenef(): ?string
    {
        return $this->prenombenef;
    }

    public function setPrenombenef(string $prenombenef): self
    {
        $this->prenombenef = $prenombenef;

        return $this;
    }

    public function getTelephonebenef(): ?int
    {
        return $this->telephonebenef;
    }

    public function setTelephonebenef(int $telephonebenef): self
    {
        $this->telephonebenef = $telephonebenef;

        return $this;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @return Collection|Commissions[]
     */
    public function getCommissions(): Collection
    {
        return $this->commissions;
    }

    public function addCommission(Commissions $commission): self
    {
        if (!$this->commissions->contains($commission)) {
            $this->commissions[] = $commission;
            $commission->setTransaction($this);
        }

        return $this;
    }

    public function removeCommission(Commissions $commission): self
    {
        if ($this->commissions->removeElement($commission)) {
            // set the owning side to null (unless already changed)
            if ($commission->getTransaction() === $this) {
                $commission->setTransaction(null);
            }
        }

        return $this;
    }

    public function getComptederetrait(): ?Comptes
    {
        return $this->comptederetrait;
    }

    public function setComptederetrait(?Comptes $comptederetrait): self
    {
        $this->comptederetrait = $comptederetrait;

        return $this;
    }

    public function getCnireceveur(): ?int
    {
        return $this->cnireceveur;
    }

    public function setCnireceveur(int $cnireceveur): self
    {
        $this->cnireceveur = $cnireceveur;

        return $this;
    }

    
  
}
