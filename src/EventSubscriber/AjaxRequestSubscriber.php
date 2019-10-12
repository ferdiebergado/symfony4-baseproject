<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelInterface;

class AjaxRequestSubscriber implements EventSubscriberInterface
{
    /** @var KernelInterface */
    private $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @inheritDoc
     */
    public function onKernelResponse(FilterResponseEvent $event): void
    {
        if (! $this->kernel->isDebug()) {
            return;
        }

        $response = $event->getResponse();
        $response->headers->set('Symfony-Debug-Toolbar-Replace', 1);
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents(): array
    {
        return ['kernel.response' => 'onKernelResponse'];
    }
}
