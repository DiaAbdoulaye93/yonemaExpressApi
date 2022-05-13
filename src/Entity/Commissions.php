<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CommissionsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 * collectionOperations={
 *  "get"={
      *   "normalization_context"={"groups"={"commissions:read"}},
      *    "path"="/commissions"
      *   },
      * "make_transaction"={ 
      *        "denormalization_context"={"groups"={"commissions:write"}},
      *         "method"="POST",
      *         "path"="/commissions",
      *      }
      *    
      *  },
      * itemOperations={
      *   "get"={
      *   "normalization_context"={"groups"={"commissions:read"}},
      *   "path"="/commissions/{id}"
      *   },
      *   "retrait"={
      *   "method"= "put",
      *    "path"= "/commissions"
      *   },
      *  "remov_transaction"=
      *      {
      *         "method"="delete",
      *         "path"="/admin/commissions/{id}"
      *      }
      * })
 * @ORM\Entity(repositoryClass=CommissionsRepository::class)
 */
class Commissions
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"transactions:read", "transactions:write", "commissions:read", "commissions:write"})
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     * @Groups({"transactions:read", "transactions:write", "commissions:read","commissions:write"})
     */
    public $montant;

    /**
     * @ORM\ManyToOne(targetEntity=Transactions::class, inversedBy="commissions")
     * @ORM\JoinColumn(nullable=false)
     *  @Groups({"commissions:read","commissions:write"})
     */
    private $transaction;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"transactions:read", "transactions:write", "commissions:read", "commissions:write"})
     */
    public $transactiontype;

    /**
     * @ORM\Column(type="date")
     * @Groups({"transactions:read", "transactions:write","commissions:read","commissions: read"})
     */
    private $createAt;

    public function getId(): ?int
    {
        return $this->id;
    }
 
    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getTransaction(): ?Transactions
    {
        return $this->transaction;
    }

    public function setTransaction(?Transactions $transaction): self
    {
        $this->transaction = $transaction;

        return $this;
    }

    public function getTransactiontype(): ?string
    {
        return $this->transactiontype;
    }

    public function setTransactiontype(string $transactiontype): self
    {
        $this->transactiontype = $transactiontype;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }
}
