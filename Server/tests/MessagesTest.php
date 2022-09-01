<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Message;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class MessagesTest extends ApiTestCase
{

    use RefreshDatabaseTrait;

    public function testGetCollection(): void
    {
        $response = static::createClient()->request('GET', '/api/messages');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            '@context' => '/api/contexts/Message',
            '@id' => '/api/messages',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 100,
        ]);

        $this->assertCount(30, $response->toArray()['hydra:member']);
        $this->assertMatchesResourceCollectionJsonSchema(Message::class);
    }

    public function testCreateMessage(): void
    {
        $response = static::createClient()->request('POST', '/api/messages', ['json' => [
            'name' => 'Vojta Hired',
            'text' => 'Hello'
        ]]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/api/contexts/Message',
            '@type' => 'Message',
            'name' => 'Vojta Hired',
            'text' => 'Hello'
        ]);
        $this->assertMatchesRegularExpression('~^/api/messages/\d+$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(Message::class);
    }

    public function testCreateInvalidMessage(): void
    {
        static::createClient()->request('POST', '/api/messages', ['json' => [
            'name' => '',
            'text' => ''
        ]]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            '@context' => '/api/contexts/ConstraintViolationList',
            '@type' => 'ConstraintViolationList',
            'hydra:title' => 'An error occurred',
            'hydra:description' => "name: This value should not be blank.\ntext: This value should not be blank."
        ]);
    }
}