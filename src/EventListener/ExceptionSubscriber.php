<?php

namespace App\EventListener;

use App\Exception\ValidationException;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\SerializerInterface;

class ExceptionSubscriber implements EventSubscriberInterface
{
    private $logger;
    private $serializer;

    public function __construct(LoggerInterface $logger, SerializerInterface $serializer)
    {
        $this->logger = $logger;
        $this->serializer = $serializer;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException'
        ];
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $ex = $event->getException();

        $this->logger->info('ExceptionSubscriber is looking at a ' . get_class($ex));

        if ($ex instanceof ValidationException) {
            $data = $this->serializer->serialize(
                ['violations' => $ex->getErrors(), 'message' => $ex->getMessage()],
                'json'
            );

            $event->setResponse(new JsonResponse($data, $ex->getStatusCode(), [], true));
        }
   }
}
