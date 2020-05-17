<?php

namespace Drupal\only_admin;

use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Theme\ThemeNegotiatorInterface;

/**
 * Switch theme for user login route.
 */
class OnlyAdminThemeSwitcher implements ThemeNegotiatorInterface {

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactory
   */
  protected $configFactory;

  /**
   * OnlyAdminThemeSwitcher constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactory $configFactory
   *   The config factory.
   */
  public function __construct(ConfigFactory $configFactory) {
    $this->configFactory = $configFactory;
  }

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
    $config = $this
      ->configFactory
      ->get('system.theme');
    return $config->get('admin') ?? $config->get('default') ?? 'seven';
  }

}
