<?php declare(strict_types = 1);

namespace UptimeProject\Dns\Handlers;

interface ResolveHandlerInterface
{
    public function resolve(string $host, string $recordType, ?string $nameServer = null) : ?string;
}
