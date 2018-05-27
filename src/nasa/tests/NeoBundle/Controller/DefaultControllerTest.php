<?php

namespace NeoBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testHazardous()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/neo/hazardous');
        $response = $client->getResponse();
        $content = $response->getContent();
        $responseData = json_decode($content, true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertJson($content);
        $this->assertTrue(is_array($responseData));
    }

    public function testFastest()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/neo/fastest');
        $response = $client->getResponse();
        $content = $response->getContent();
        $responseData = json_decode($content, true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertJson($content);
        $this->assertTrue(is_array($responseData));
    }

    public function testBestYear()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/neo/best-year');
        $response = $client->getResponse();
        $content = $response->getContent();
        $responseData = json_decode($content, true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertJson($content);
        $this->assertTrue(is_array($responseData));
        $this->assertTrue(isset($responseData['year']));
    }

    public function testMonthYear()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/neo/best-month');
        $response = $client->getResponse();
        $content = $response->getContent();
        $responseData = json_decode($content, true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertJson($content);
        $this->assertTrue(is_array($responseData));
        $this->assertTrue(isset($responseData['month']));
    }

}
