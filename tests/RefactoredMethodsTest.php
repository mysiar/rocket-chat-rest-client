<?php
declare(strict_types=1);

namespace RocketChat\Tests;

use PHPUnit\Framework\TestCase;
use RocketChat\Channel;
use RocketChat\Client;
use RocketChat\Config;
use RocketChat\Exception;
use RocketChat\User;

class RefactoredMethodsTest extends TestCase
{
    /** @var Config */
    private $config;

    protected function setUp(): void
    {
        $this->config = new Config(
            getenv("ROCKET_CHAT_REST_CLIENT_URL"),
            getenv("ROCKET_CHAT_REST_CLIENT_API_ROOT")
        );
    }

    public function testClientCreate(): void
    {
        $client = $this->getClient();
        $this->assertInstanceOf(Client::class, $client);

        $version = $client->version();
        $this->assertNotEmpty($version);
    }

    /**
     * @throws \Httpful\Exception\ConnectionErrorException
     */
    public function testUser(): void
    {
        $user = $this->getUser();

        $this->assertInstanceOf(User::class, $user);

        $this->assertTrue($user->login());

    }

    /**
     * @throws \Httpful\Exception\ConnectionErrorException
     */
    public function testUserLoginThrowsException(): void
    {
        $user = new User($this->getClient(), 'user', 'password');

        $this->expectException(Exception::class);
        $user->login();
    }

    public function testChannel(): void
    {
        $channel = new Channel(
            getenv("ROCKET_CHAT_REST_CLIENT_CHANNEL"),
            $this->getUser()
        );

        $this->assertInstanceOf(Channel::class, $channel);
        $this->assertNotEmpty($channel->info());

        $this->assertTrue(
            $channel->postMessage(
                'Test from refactored lib: https://github.com/mysiar/rocket-chat-rest-client/tree/dev '
            )
        );
    }


    private function getClient(): Client
    {
        return new Client($this->config);
    }

    private function getUser(): User
    {
        return new User(
            $this->getClient(),
            getenv("ROCKET_CHAT_REST_CLIENT_USER"),
            getenv("ROCKET_CHAT_REST_CLIENT_PASSWORD")
        );
    }
}
