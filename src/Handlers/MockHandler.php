<?php declare(strict_types = 1);

namespace UptimeProject\Dns\Handlers;

final class MockHandler implements ResolveHandlerInterface
{
    /* @var string */
    private $mockResponse;

    public function setMockResponse(string $response) : void
    {
        $this->mockResponse = $response;
    }

    public function resolve(string $host, string $recordType, ?string $nameServer = null) : ?string
    {
        return $this->mockResponse;
    }
}
