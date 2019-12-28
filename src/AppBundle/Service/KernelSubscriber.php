<?php

namespace AppBundle\Service;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class KernelSubscriber implements EventSubscriberInterface {

    public static function getSubscribedEvents() {
        return [
            KernelEvents::RESPONSE => [
                ['clearBrowserCache', 434255],
            ],
        ];
    }

    public function clearBrowserCache(FilterResponseEvent $event) {

        $response = $event->getResponse();
        $headers = $response->headers;

        $headers->addCacheControlDirective('no-cache', true);
        $headers->addCacheControlDirective('max-age', 0);
        $headers->addCacheControlDirective('must-revalidate', true);
        $headers->addCacheControlDirective('no-store', true);

    }

}