<?php
declare(strict_types=1);

namespace RocketChat;

class Config
{
    /** @var string */
    private $url;

    /** @var string */
    private $apiRoot;

    public function __construct(string $url, string $apiRoot)
    {
        $this->url = $url;
        $this->apiRoot = $apiRoot;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getApiRoot(): string
    {
        return $this->apiRoot;
    }
}
