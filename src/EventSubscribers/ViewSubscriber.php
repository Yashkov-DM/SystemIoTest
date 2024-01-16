<?php

declare(strict_types=1);

namespace App\EventSubscribers;

use App\Responses\ResponseInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

readonly class ViewSubscriber implements EventSubscriberInterface
{
    public function __construct(private NormalizerInterface $normalizer)
    {
    }

    /**
     * @throws ExceptionInterface
     */
    public function onKernelView(ViewEvent $event): void
    {
        $controllerResult = $event->getControllerResult();

        if ($controllerResult instanceof ResponseInterface) {
            $data = $this->normalizer->normalize($controllerResult);

            $response = new JsonResponse($data, $controllerResult->getResponseCode());
            $event->setResponse($response);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => [
                ['onKernelView', 64],
            ],
        ];
    }
}
