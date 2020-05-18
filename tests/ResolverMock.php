<?php declare(strict_types = 1);

namespace UptimeProject\Dns\Tests;

use UptimeProject\Dns\Resolver\ResolverInterface;

class ResolverMock implements ResolverInterface
{
    /* @var string */
    protected $mockResponse;

    public function setMockResponse(string $response) : void
    {
        $this->mockResponse = $response;
    }

    public function resolve(string $host, string $recordType, ?string $nameServer = null) : ?string
    {
        return $this->mockResponse;
    }
}
