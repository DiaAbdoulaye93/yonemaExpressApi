<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Comptes;
use App\Entity\User;
use App\Repository\ProfileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    #[Route('/user', name: 'user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route(path="/api/sendIt/connectedUser", name="getConnectedUser", methods="get"),
     * @throws ExceptionInterface
     */
    public function getUserConnected(SerializerInterface $serializer): JsonResponse
    {
        $user = $this->getUser();
        //  dd($user);
        // $user = $serializer->normalize($user, 'JSON', ['groups' => 'getConnectedUser']);
        return new JsonResponse($user, Response::HTTP_OK);
    }
    /**
     * @Route(path="/api/inscription", name="Inscription", methods="post"),
     * @throws ExceptionInterface
     */
    public function Inscription(Request $request,  ProfileRepository $profil, EntityManagerInterface $manager,UserPasswordEncoderInterface $passwordEncoder)
    {
        $data =$request->request->all(); 
        $user = new Client  ();
        $compte = new Comptes();
        $compte->setNumero(rand(1000000,9999999));
        $compte->setCreatedAt(new \DateTime());
        $compte ->setStatut(false);
        $compte->setSolde(0);
        $profilClient = $profil->findBy(['libelle'=>'Client']); 
       
        foreach($data as $tableColumn => $value)
        {
            if($tableColumn != "password"){
                $setProperty='set'.ucfirst($tableColumn);
                $user->$setProperty($value);
            }
            $password=$data['password'];
            $user->setPassword($passwordEncoder->encodePassword( $user, $password));
        }
        $user->setProfil($profilClient[0]);
        $user->setCompte($compte);
        $manager->persist($compte);
        $manager->persist($user);
        $manager->flush();
        return new JsonResponse("Inscription nouvel utilisateur reussi", Response::HTTP_CREATED, [], true);
  
    }
    
}
