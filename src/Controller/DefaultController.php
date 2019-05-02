<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpKernel\Kernel;

class DefaultController extends AbstractController
{
    public function index(KernelInterface $kernel)
    {
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