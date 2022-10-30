<?php

namespace App\Tests\functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ProjectControllerTest extends WebTestCase
{
    public function testShouldGetApiResponse()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/projects',
            [],
            [],
            [],
            '[]'
        );

        $this->assertEquals(Response::HTTP_CREATED, $client->getResponse()->getContent());
    }
}
