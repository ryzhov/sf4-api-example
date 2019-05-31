<?php

namespace App\EventListener;

use App\Exception\ValidationException;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
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
        $serializer = $this->serializer;

        $this->logger->info('ExceptionSubscriber is looking at a ' . get_class($ex));

       if ($ex instanceof HttpException) {
            $data = ['code' => $ex->getStatusCode(), 'message' => $ex->getMessage()];

            if ($ex instanceof ValidationException) {
                $data['violations'] = $ex->getErrors();
            }
       } else {
           return;
       }

       $json = $serializer->serialize($data, 'json', ['json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS]);
       $event->setResponse(new JsonResponse($json, $ex->getStatusCode(), [], true));
   }
}
