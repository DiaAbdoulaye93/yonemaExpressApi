<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;
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
}
