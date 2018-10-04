<?php

namespace Drupal\event\EventSubscriber;

use Drupal\Core\Url;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;

class RequestSubscriber implements EventSubscriberInterface{
   public function checkAuthStatus(GetResponseEvent $event) {
    if (
      \Drupal::currentUser()->isAnonymous() &&
      \Drupal::routeMatch()->getRouteName() != 'user.login' ) {
    
      $url = Url::fromRoute('user.login')->toString();
      $response = new RedirectResponse($url, 301);
      $response->send();
    }
  }
  public static function getSubscribedEvents() {
    return [
            KernelEvents::REQUEST => ['checkAuthStatus']
        ];
    }
}