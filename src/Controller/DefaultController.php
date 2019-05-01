<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;

class DefaultController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index(KernelInterface $kernel, LoggerInterface $logger)
    {
        $logger->debug('DefaultController::index');
        return $this->json([
            'kernel_version' => Kernel::VERSION,
            'environment' => $kernel->getEnvironment(),
            'debug' => $kernel->isDebug() ? 'true' : 'false',
            'project_dir' => $kernel->getProjectDir(),
            'start_time' => $kernel->getStartTime(),
            'charset' => $kernel->getCharset()
        ]);
    }
}