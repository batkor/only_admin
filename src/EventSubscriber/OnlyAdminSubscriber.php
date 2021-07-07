<?php

namespace Drupal\only_admin\EventSubscriber;

use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Url;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * The event subscriber for redirect anonymous users.
 */
class OnlyAdminSubscriber implements EventSubscriberInterface {

  /**
   * The account proxy.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $account;

  /**
   * The current route match.
   *
   * @var \Drupal\Core\Routing\CurrentRouteMatch
   */
  protected $currentRouteMatch;

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

  /**
   * Kernel response event handler.
   *
   * @param \Symfony\Component\HttpKernel\Event\ResponseEvent $event
   *   Response event.
   */
  public function onKernelResponse(ResponseEvent $event) {
    // Skip if user authenticated.
    if ($this->account->isAuthenticated()) {
      return;
    }
    // Skip if current route user login.
    $allowed_routes = [
      'user.login',
      'user.reset',
      'user.reset.form',
      'user.logout',
      'user.login.http',
      'user.login_status.http',
    ];

    if (\in_array($this->currentRouteMatch->getRouteName(), $allowed_routes)) {
      return;
    }

    $event->setResponse(new RedirectResponse(Url::fromRoute('user.login')->toString()));
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      KernelEvents::RESPONSE => ['onKernelResponse'],
    ];
  }

}
