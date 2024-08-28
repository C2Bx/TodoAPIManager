<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        if ($exception instanceof MethodNotAllowedHttpException) {
            $allowedMethods = $exception->getHeaders()['Allow'] ?? 'GET, POST';
            $response = new JsonResponse([
                'error' => "Méthode non autorisée. Veuillez utiliser l'une des méthodes suivantes : $allowedMethods."
            ], Response::HTTP_METHOD_NOT_ALLOWED);            
            $event->setResponse($response);
        } elseif ($exception instanceof NotFoundHttpException) {
            $response = new JsonResponse(['error' => 'Route non trouvée.'], Response::HTTP_NOT_FOUND);
            $event->setResponse($response);
        } else {
            $response = new JsonResponse(['error' => 'Une erreur inattendue est survenue.'], Response::HTTP_INTERNAL_SERVER_ERROR);
            $event->setResponse($response);
        }
    }
}
