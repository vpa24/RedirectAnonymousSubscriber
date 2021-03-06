<?php
namespace Drupal\event\EventSubscriber;

use Drupal\Core\Url;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Session\AccountProxyInterface;

class RequestSubscriber implements EventSubscriberInterface
{
  protected $routeMatch;
  protected $accountProxy;

  public function __construct(RouteMatchInterface $route_match, AccountProxyInterface $account_proxy)
  {
    $this->routeMatch = $route_match;
    $this->accountProxy = $account_proxy;
  }
  public function checkAuthStatus(GetResponseEvent $event)
  {
    if ($this->routeMatch->getRouteName() == 'user.login'|| $this->routeMatch->getRouteName() == 'view.frontpage.page_1') {
      return;
    }
    if ($this->accountProxy->isAnonymous()) {

      $url = Url::fromRoute('user.login')->toString();
      $redirect = new RedirectResponse($url);
      $event->setResponse($redirect);
    }
  }
  public static function getSubscribedEvents()
  {
    return [
      KernelEvents::REQUEST => ['checkAuthStatus']
    ];
  }
}