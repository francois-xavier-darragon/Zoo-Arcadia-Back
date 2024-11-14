<?php

namespace App\EventListener;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Twig\Environment;

class ExceptionListener implements EventSubscriberInterface
{
    public function __construct(
        private Environment $twig,
        private Security $security
    )
    {}

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException'
        ];
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        
        if ($exception instanceof NotFoundHttpException) {
            $request = $event->getRequest();

            $isAuthorized = $this->security->isGranted('ROLE_ADMIN') ||
                   $this->security->isGranted('ROLE_VETERINARY') ||
                   $this->security->isGranted('ROLE_WORKER') || str_contains($request->getPathInfo(), '/admin');
            
            $content = $this->twig->render('bundles/TwigBundle/Exception/error404.html.twig', [
                'layout' => $isAuthorized ? 'base.html.twig' : 'base_front.html.twig'
            ]);
            
            $response = new Response($content, 404);
            $event->setResponse($response);
        }
    }
}