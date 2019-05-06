<?php

namespace App\Controller;

use App\Entity\User;
use App\DTO\UserRegistrationDto;
use App\Mapper\DTOMapper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler;

class UserController extends AbstractController
{
    /**
     * @Route("/me")
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function me(UserInterface $user)
    {
        return $this->json(['me' => $user], Response::HTTP_OK, [], ['groups' => 'user']);
    }

    /**
     * @Route("/registration", methods={"POST"})
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function registration(AuthenticationSuccessHandler $authenticationSuccessHandler, DTOMapper $mapper,
                                 UserPasswordEncoderInterface $encoder, UserRegistrationDto $userRegistrationDto)
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
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function login()
    {
        return $this->json(['check security configuration firewalls.login.json_login.check_path']);
    }

    /**
     * @Route("/logout", methods={"POST"})
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function logout(UserInterface $user, Request $request)
    {
        //TODO: put user jwt token in black list until it expired
        $data = ['user' => $user, 'logout_at' => $request->server->get('REQUEST_TIME')];
        return $this->json($data, Response::HTTP_OK, [], ['groups' => 'user']);
    }
}