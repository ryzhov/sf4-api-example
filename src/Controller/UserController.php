<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler;

class UserController extends AbstractController
{
    /**
     * @Route("/me")
     *
     * @param UserInterface $user
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function me(UserInterface $user)
    {
        return $this->json(['me' => $user], Response::HTTP_OK, [], ['groups' => 'user']);
    }

    /**
     * @Route("/login", methods={"POST"})
     *
     * @param UserInterface $user
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function login()
    {
        return $this->json(['check security configuration firewalls.login.json_login.check_path']);
    }
}