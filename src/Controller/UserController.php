<?php

namespace App\Controller;

use App\Entity\User;
use App\DTO\UserRegistrationDto;
use App\Mapper\DTOMapper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler;

class UserController extends AbstractController
{
    /**
     * @Route("/me", methods={"GET"})
     */
    public function me(UserInterface $user, Request $request): JsonResponse
    {
        $token = $request->cookies->get('token');
        return $this->json(['username' => $user->getUsername(), 'token' => $token]);
    }

    /**
     * @Route("/registration", methods={"POST"})
     */
    public function registration(AuthenticationSuccessHandler $authenticationSuccessHandler, DTOMapper $mapper,
                                 UserPasswordEncoderInterface $encoder, UserRegistrationDto $userRegistrationDto
    ): JsonResponse
    {
        $mapper->map($userRegistrationDto, $user = new User());
        $user->setPassword($encoder->encodePassword($user, $userRegistrationDto->password));

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return $authenticationSuccessHandler->handleAuthenticationSuccess($user);
    }

    /**
     * @Route("/login", methods={"POST"})
     */
    public function login() : JsonResponse
    {
        return $this->json(['check security configuration firewalls.login.json_login.check_path']);
    }

    /**
     * @Route("/logout", methods={"POST"})
     */
    public function logout(UserInterface $user, Request $request, RouterInterface $router) : JsonResponse
    {
        $data = ['username' => $user->getUsername(), 'logoutAt' => $request->server->get('REQUEST_TIME')];

        $path = $router->generate('app_user_me');
        $domain = $request->server->get('HTTP_HOST');

        $response = $this->json($data, Response::HTTP_OK, [], ['groups' => 'user']);
        $response->headers->clearCookie('token', $path, $domain);

        return $response;
    }
}