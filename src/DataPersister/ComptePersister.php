<?php
namespace App\DataPersister;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Response;
use App\Entity\Comptes;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Repository\ComptesRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
class ComptePersister implements DataPersisterInterface
{
    private $manager;
    public function __construct(EntityManagerInterface $manager, comptesRepository $comptes)
    {
        $this->manager= $manager;
        $this->compte = $comptes;
    }
    public function supports($data): bool
    {
        return $data instanceof Comptes;
    }
    public function persist($data)
    {
      //  dd($data);
        if(!($data->id))
        {
           
         $data->comptes->setCreatedAt(new \DateTime());
         if($data->comptes->solde){
         $montant=$data->comptes->solde;
         if($montant > 700000){
             $data->comptes->setSolde($montant);
         }else {
             $data->comptes->setSolde(700000);
         }
         }
        }
        else{
            $id= $data->id;
           
            $compte = $this->compte->findBy(['id'=>$id]);
            $AddedSomme= $compte[0]->solde;
            $MainObject = $this->manager->getUnitOfWork();
            $OlderData = $MainObject->getOriginalEntityData($compte[0]);
            $OlderSolde= $OlderData['solde'];
            $newSolde= $OlderSolde + $AddedSomme;
            
            $data->solde =$newSolde;
        }
   
       $this->manager->flush();
      return $data;
    }
    public function remove($data)
    {
       $data->setIsdeleted(true);
       $this->manager->flush();
    }
}