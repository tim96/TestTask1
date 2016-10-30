<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FrontendControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testNewAlbum()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/album');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testAllAlbums()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/albums');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testEditAlbum()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/album/2/edit');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testEditAlbumNonDigitalParam()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/album/sds/edit');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testShowAlbum()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/album/2/show');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testShowAlbumNonDigitalParam()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/album/sds/show');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
}
