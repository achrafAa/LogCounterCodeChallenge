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

    public function testCountEndpointWithoutParams(): void
    {
        $client = static::createClient();
        $client->request('GET', '/count');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('counter', $responseData);
        $this->assertIsInt($responseData['counter']);

        $this->assertEquals(20, $responseData['counter']);
    }

    public function testCountEndpointWithServiceNameParam(): void
    {
        $client = static::createClient();
        $serviceNames = implode(',', ['USER-SERVICE']);

        $client->request('GET', '/count', [
            'serviceNames' => $serviceNames,
        ]);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('counter', $responseData);
        $this->assertIsInt($responseData['counter']);

        $this->assertEquals(14, $responseData['counter']);
    }

    public function testCountEndpointWithStatusCodeParam(): void
    {
        $client = static::createClient();
        $client->request('GET', '/count', [
            'statusCode' => 400,
        ]);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('counter', $responseData);
        $this->assertIsInt($responseData['counter']);

        $this->assertEquals(4, $responseData['counter']);
    }

    public function testCountEndpointWithStartDateParam(): void
    {
        $client = static::createClient();
        $client->request('GET', '/count', [
            'startDate' => '2018-08-18',
        ]);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('counter', $responseData);
        $this->assertIsInt($responseData['counter']);

        $this->assertEquals(6, $responseData['counter']);
    }

    public function testCountEndpointWithEndDateParam(): void
    {
        $client = static::createClient();
        $client->request('GET', '/count', [
            'endDate' => '2018-08-18',
        ]);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('counter', $responseData);
        $this->assertIsInt($responseData['counter']);

        $this->assertEquals(14, $responseData['counter']);
    }

    public function testCountEndpointWithEndDateAndServiceNameParams(): void
    {
        $client = static::createClient();

        $serviceNames = implode(',', ['USER-SERVICE']);

        $client->request('GET', '/count', [
            'serviceNames' => $serviceNames,
            'endDate' => '2018-08-18',
        ]);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('counter', $responseData);
        $this->assertIsInt($responseData['counter']);

        $this->assertEquals(9, $responseData['counter']);
    }
}
