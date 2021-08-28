<?php

namespace Drupal\Tests\only_admin\Kernel;

use Drupal\Core\Session\AnonymousUserSession;
use Drupal\KernelTests\Core\Entity\EntityKernelTestBase;
use Drupal\KernelTests\KernelTestBase;
use Drupal\Tests\UiHelperTrait;
use Drupal\Tests\user\Traits\UserCreationTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Tests redirects.
 *
 * @group only_admin
 */
class RedirectTest extends EntityKernelTestBase {
  
  /**
   * {@inheritdoc}
   */
  protected static $modules = ['system', 'user', 'only_admin'];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * Test redirect for anonymous user.
   */
  public function testAnonymousUserRedirects(): void {
    // Redirect.
    \Drupal::currentUser()->setAccount(new AnonymousUserSession());
    $subRequest = Request::create('/user');
    $response = $this->container->get('http_kernel')->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
    $this->assertEquals(302, $response->getStatusCode());
    $this->assertStringContainsString('/user/login', $response->getTargetUrl());
    // Disallow redirect for allowed route.
    $subRequest = Request::create('/user/login');
    $response = $this->container->get('http_kernel')->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
    $this->assertEquals(200, $response->getStatusCode());
  }

  /**
   * Test authorization user.
   */
  public function testAuthorizationUser(): void {
    $user = $this->drupalCreateUser();
    $this->setCurrentUser($user);
    $subRequest = Request::create('/user');
    $response = $this->container->get('http_kernel')->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
    $this->assertStringContainsString('/user/' . $user->id(), $response->getTargetUrl());
  }
  
}
