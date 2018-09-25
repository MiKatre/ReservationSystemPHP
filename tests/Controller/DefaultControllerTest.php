<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
  public function testHomepage() {
    // Test status code
    $client = static::createClient();
    $client->request('GET', '/');
    $this->assertEquals(200, $client->getResponse()->getStatusCode());

    // Check page content
    $crawler = $client->request('GET', '/');
    $this->assertGreaterThan(0, $crawler->filter('html:contains("Billetterie")')->count());
    $this->assertGreaterThan(0, $crawler->filter('a:contains("Commander")')->count());
  }

  public function testFormPageIsUp(){
    $client = static::createClient();
    $client->request('GET', '/form');
    $this->assertEquals(200, $client->getResponse()->getStatusCode());
  }
}