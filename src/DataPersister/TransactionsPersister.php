<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Commissions;
use App\Entity\Comptes;
use App\Entity\Transactions;
use App\Repository\CommissionsRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TransactionsRepository;
use App\Repository\ComptesRepository;
use Symfony\Component\HttpFoundation\Request;

class TransactionsPersister implements DataPersisterInterface
{
  private $manager;
  public function __construct(EntityManagerInterface $manager, TransactionsRepository $transaction, ComptesRepository $compte)
  {
    $this->manager = $manager;
    $this->transaction = $transaction;
    $this->comptes = $compte;
  }
  public function supports($data, array $context = []): bool
  {

    return $data instanceof Transactions;
  }

  public function persist($data, array $context = [])
  {

    /*  $uniqid = uniqid();
        $rand_start = rand(1,9);
        $randNumber = substr($uniqid,$rand_start,4); */
    //dd($data);
    if (!($data->code)) {
      $montant = $data->total;
      if ($montant <= $data->compte->solde) {
        $randNumber = rand(1000, 9999);
        $data->setCode($randNumber);
        $data->setCreatedAt(new \DateTime());
        $data->compte->solde = $data->compte->solde - $montant;
        $commissions = new Commissions();
        $commissionMontant =  ($data->frais) / 10;
        $commissions->setCreateAt(new \DateTime());
        $commissions->setMontant($commissionMontant);
        $commissions->setTransactiontype('depot');
        $data->addCommission($commissions); // commissions[0] = $commissions;
      
        $this->manager->persist($data);
      } else {
        return new  JsonResponse('Desolé le montant que vous souhaitez est indisponible en ce moment', Response::HTTP_FORBIDDEN, [], true);
      }
      $this->manager->flush();
      return $data;
    } else {
      $code = $data->code;
          /** On recupere la transaction concernée*/
      $transaction = $this->transaction->findBy(['code' => $code]);
      if ($transaction) {
        if (!($transaction[0]->date_retrait)) {
          $transaction[0]->setDateRetrait(new \DateTime());
          /** Travaills sur les paramtre de la commision et du type de transaction */
          $commissions = new Commissions();
          $commissionMontant =  ($transaction[0]->frais) / 5;
          $commissions->setCreateAt(new \DateTime());
          $commissions->setMontant($commissionMontant);
          $commissions->setTransactiontype('retrait');
          /**On recupere le montant de la transaction puis on par prendre l'entité transaction pour lui donner le montnant */
          $montantTransaction = $transaction[0]->montant;
          $CurrentCompte = $data->comptederetrait->solde;
          $newSolde = $CurrentCompte + $montantTransaction;
          $data->comptederetrait->solde = $newSolde;
          $transaction[0]->comptederetrait = $data->comptederetrait;
          $transaction[0]->comptederetrait;
          $data = $transaction;
          /** On ajuote ici le type de transaction et le montant de la commision*/
          $data[0]->addCommission($commissions);
        } else {
          return new  JsonResponse('Cette transaction a deja été reglée, Merci de votre fidélité', Response::HTTP_FORBIDDEN, [], true);
        }
      } else {
        return new  JsonResponse('Le code que vous avez saisie est invalide', Response::HTTP_FORBIDDEN, [], true);
      }
      $this->manager->flush();
      return new JsonResponse("Le retrait a été effetue avec success, Lazy est Content de vous", Response::HTTP_OK);
    }
  }

  public function remove($data, array $context = [])
  {
    // call your persistence layer to delete $data
  }
}
