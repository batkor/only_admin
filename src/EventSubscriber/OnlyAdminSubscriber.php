<?php

namespace Drupal\only_admin\EventSubscriber;

use Drupal\asti\Utility\Asti;
use Drupal\Core\Path\PathMatcher;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Url;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class OnlyAdminSubscriber implements EventSubscriberInterface {

  /**
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $account;

  public function __construct(AccountInterface $account) {
    $this->account = $account;
  }
}
