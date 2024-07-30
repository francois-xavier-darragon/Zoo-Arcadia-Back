<?php

namespace App\Service;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Bundle\SecurityBundle\Security;

class AdminAccessService
{
    private $security;
    private $router;

    public function __construct(Security $security, RouterInterface $router)
    {
        $this->security = $security;
        $this->router = $router;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        $path = $request->getPathInfo();

        if (strpos($path, '/admin') === 0) {
    
            if (!$this->security->isGranted('ROLE_ADMIN') && !$this->security->isGranted('ROLE_VETERINARY') && !$this->security->isGranted('ROLE_WORKER')) {
                $event->setResponse(new RedirectResponse($this->router->generate('app_home')));
            }
        }
    }
}