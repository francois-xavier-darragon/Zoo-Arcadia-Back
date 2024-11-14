<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class ExceptionListener implements EventSubscriberInterface
{
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

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
            
            if (str_starts_with($request->getPathInfo(), '/admin')) {
                $content = $this->twig->render('bundles/TwigBundle/Exception/admin/error404.html.twig');
            } else {
                $content = $this->twig->render('bundles/TwigBundle/Exception/front/error404.html.twig');
            }
            
            $response = new Response($content, 404);
            $event->setResponse($response);
        }
    }
}