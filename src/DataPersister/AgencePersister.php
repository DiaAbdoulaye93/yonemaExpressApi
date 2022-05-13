<?php
namespace App\DataPersister;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Response;
use App\Entity\Agence;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Repository\AgenceRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AgencePersister implements DataPersisterInterface
{
    private $manager;
    public function __construct(EntityManagerInterface $manager, AgenceRepository $agence, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->manager= $manager;
        $this->agence = $agence;
        $this->passwordEncoder= $passwordEncoder;
    }
    public function supports($data): bool
    {
        return $data instanceof Agence;
    }
    public function persist($data)
    {
        if(!($data->id))
        {
          
            $data->comptes->setCreatedAt(new \DateTime());
            $randNumber= rand( 100000,999999 );
            $data->comptes->setNumero($randNumber);
            if($data->comptes->solde){
                $montant=$data->comptes->solde;
                if($montant > 700000){
                    $data->comptes->setSolde($montant);
                }else {
                    $data->comptes->setSolde(700000);
                }
            }
            $user= $data->users[0];
            
                    $profil= $user->profil->libelle;
                    $password=$user->password;
                    $user->setPassword($this->passwordEncoder->encodePassword( $user, $password));
                    $user->setRoles(array("ROLE_".$profil));
                    $this->manager->persist($data);
                     

                
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
            
            
        // dd($user);
       $this->manager->flush();
      return $data;
    }
    public function remove($data)
    {
       $data->setIsdeleted(true);
       $this->manager->flush();
    }
}