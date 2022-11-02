<?php

namespace App\Tests\functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ProjectControllerTest extends WebTestCase
{
    public function testShouldGetApiCreateResponse()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/projects',
            [],
            [],
            [],
            '{"title": "project","numberOfGroups": 5,"studentsPerGroup": 2}'
        );

        $this->assertEquals(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());
    }

    public function testShouldGetApiReadResponse()
    {
        $client = static::createClient();
        $client->request(
            'GET',
            '/projects/1000',
        );

        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $client->getResponse()->getStatusCode());
    }
}
