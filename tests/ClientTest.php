<?php
declare(strict_types=1);

namespace RocketChat\Tests;

use PHPUnit\Framework\TestCase;
use RocketChat\Client;
use RocketChat\Config;

class ClientTest extends TestCase
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
        $client = new Client($this->config);
        $this->assertInstanceOf(Client::class, $client);

        $version = $client->version();
        $this->assertNotEmpty($version);
    }
}
