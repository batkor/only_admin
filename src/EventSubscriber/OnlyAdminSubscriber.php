<?php

namespace Drupal\only_admin\EventSubscriber;

use Drupal\Core\Cache\CacheableRedirectResponse;
use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Url;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * The event subscriber for redirect anonymous users.
 */
class OnlyAdminSubscriber implements EventSubscriberInterface {

  /**
   * The account proxy.
   */
  protected AccountInterface $account;

  /**
   * The current route match.
   */
  protected CurrentRouteMatch $currentRouteMatch;

  /**
   * OnlyAdminSubscriber constructor.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The account proxy.
   * @param \Drupal\Core\Routing\CurrentRouteMatch $currentRouteMatch
   *   The current route match.
   */
  public function __construct(AccountInterface $account, CurrentRouteMatch $currentRouteMatch) {
    $this->account = $account;
    $this->currentRouteMatch = $currentRouteMatch;
  }

  public function onKernelRequest(RequestEvent $event): void {
    // Skip if user authenticated.
    if ($this->account->isAuthenticated()) {
      return;
    }
    // Skip if current route user login.
    $allowed_routes = [
      'user.login',
      'user.reset',
      'user.reset.login',
      'user.reset.form',
      'user.logout',
      'user.login.http',
      'user.login_status.http',
    ];

    if (\in_array($this->currentRouteMatch->getRouteName(), $allowed_routes)) {
      return;
    }
    $event->setResponse(new CacheableRedirectResponse(Url::fromRoute('user.login')->toString()));
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array {
    return [
      KernelEvents::REQUEST => ['onKernelRequest'],
    ];
  }

}
