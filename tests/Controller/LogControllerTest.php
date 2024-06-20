<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LogControllerTest extends WebTestCase
{
    public function testCountEndpointRouteExists(): void
    {
        $client = static::createClient();
        $client->request('GET', '/count');

        $this->assertNotEquals(404, $client->getResponse()->getStatusCode());
    }

    //    public function testCountEndpointWithoutParams(): void
    //    {
    //        $client = static::createClient();
    //        $client->request('GET', '/count');
    //
    //        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    //        $this->assertJson($client->getResponse()->getContent());
    //
    //        $responseData = json_decode($client->getResponse()->getContent(), true);
    //        $this->assertArrayHasKey('counter', $responseData);
    //        $this->assertIsInt($responseData['counter']);
    //
    //        $this->assertEquals(20, $responseData['counter']);
    //    }
}
