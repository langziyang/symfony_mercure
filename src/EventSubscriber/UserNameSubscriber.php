<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class UserNameSubscriber implements EventSubscriberInterface
{
    public function onKernelRequest(RequestEvent $event): void
    {
        $sessions = $event->getRequest()->getSession();
        if (!$sessions->has('username')) {
            $sessions->set('username', 'user' . random_int(1000, 9999));
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }
}
