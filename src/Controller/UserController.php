<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Comptes;
use App\Entity\User;
use App\Repository\ComptesRepository;
use App\Repository\ProfileRepository;
use App\Repository\UserRepository;
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

    public function __construct(ProfileRepository $profil, EntityManagerInterface $manager, UserPasswordEncoderInterface $passwordEncoder, ComptesRepository $compte, UserRepository $userRepository)
    {
        $this->profil = $profil;
        $this->em = $manager;
        $this->pwd = $passwordEncoder;
        $this->compte = $compte;
        $this->user = $userRepository;
    }
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
    public function Inscription( Request $request)
    {
        $data = $request->request->all();
        $user = new Client();
        $compte = new Comptes();
        $compte->setNumero(rand(1000000, 9999999));
        $compte->setCreatedAt(new \DateTime());
        $compte->setStatut(false);
        $compte->setSolde(0);
        $profilClient = $this->profil->findBy(['libelle' => 'Client']);

        foreach ($data as $tableColumn => $value) {
            if ($tableColumn != "password") {
                $setProperty = 'set' . ucfirst($tableColumn);
                $user->$setProperty($value);
            }
            $password = $data['password'];
            $user->setPassword($this->pwd->encodePassword($user, $password));
        }
        $user->setProfil($profilClient[0]);
        $user->setCompte($compte);
        $this->em->persist($compte);
        $this->em->persist($user);
        $this->em->flush();
        return new JsonResponse("Inscription nouvel utilisateur reussi", Response::HTTP_CREATED, [], true);
    }
    /**
     * @Route(path="/api/transfert", name="betweenTwoClient", methods="post"),
     */
    public function transfert( Request $request): JsonResponse
    {
        $data = $request->request->all();
        $user = $this->getUser();
        $compte = new Comptes();
        $senderAmount =$user->compte->solde - $data['montant'];
        $receiverAmount = $this->user->findBy(['telephone' => $data['telephone']])[0]->compte->solde + $data['montant'] ;
        dd($senderAmount,$receiverAmount);
        // $user = $serializer->normalize($user, 'JSON', ['groups' => 'getConnectedUser']);
        return new JsonResponse($user, Response::HTTP_OK);
    }
}
