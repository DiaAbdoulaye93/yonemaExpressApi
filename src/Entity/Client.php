<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 */
class Client  extends User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\OneToOne(targetEntity=Comptes::class, cascade={"persist", "remove"})
     */
    public $compte;

    /**
     * @ORM\OneToMany(targetEntity=TransactionsClient::class, mappedBy="receiver")
     */
    private $transactionsClients;

   
    public function __construct()
    {
        parent::__construct();
        $this->receiver = new ArrayCollection();
        $this->transactionsClients = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection|TransactionsClient[]
     */
    public function getTransactionsClients(): Collection
    {
        return $this->transactionsClients;
    }

    public function addTransactionsClient(TransactionsClient $transactionsClient): self
    {
        if (!$this->transactionsClients->contains($transactionsClient)) {
            $this->transactionsClients[] = $transactionsClient;
            $transactionsClient->setReceiver($this);
        }

        return $this;
    }

    public function removeTransactionsClient(TransactionsClient $transactionsClient): self
    {
        if ($this->transactionsClients->removeElement($transactionsClient)) {
            // set the owning side to null (unless already changed)
            if ($transactionsClient->getReceiver() === $this) {
                $transactionsClient->setReceiver(null);
            }
        }

        return $this;
    }
}
