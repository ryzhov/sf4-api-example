<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RequestStack;


class AuthenticationSuccessListener
{
    const TOKEN_COOKIE_TTL = 3600;
    /**
     * @var ObjectNormalizer
     */
    private $normalizer;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(ObjectNormalizer $normalizer, RequestStack $requestStack, RouterInterface $router)
    {
        $this->normalizer = $normalizer;
        $this->requestStack = $requestStack;
        $this->router = $router;
    }

    /**
     * @throws \Exception Emits Exception in case of an error.
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $request = $this->requestStack->getCurrentRequest();
        $response = $event->getResponse();
        $data = $event->getData();

        $expire = 0; //(int)$request->server->get('REQUEST_TIME') + self::TOKEN_COOKIE_TTL;
        $path = $this->router->generate('app_user_me');
        $domain = $request->server->get('HTTP_HOST');
        $response->headers->setCookie(
            Cookie::create('token', $data['token'], $expire, $path, $domain, null, true, false, Cookie::SAMESITE_NONE)
        );
    }
}