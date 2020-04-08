<?php

namespace Drupal\only_admin;

use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Theme\ThemeNegotiatorInterface;

/**
 * Switch theme for user login route.
 */
class OnlyAdminThemeSwitcher implements ThemeNegotiatorInterface {

  /**
   * {@inheritdoc}
   */
  public function applies(RouteMatchInterface $route_match) {
    return $route_match->getRouteName() == 'user.login';
  }

  /**
   * {@inheritdoc}
   */
  public function determineActiveTheme(RouteMatchInterface $route_match) {
    if ($route_match->getRouteName() == 'user.login') {
      return 'seven';
    }

    return NULL;
  }
}
