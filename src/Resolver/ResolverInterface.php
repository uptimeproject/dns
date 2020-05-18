<?php declare(strict_types = 1);

namespace UptimeProject\Dns\Resolver;

interface ResolverInterface
{
    public function resolve(string $host, string $recordType, ?string $nameServer = null) : ?string;
}
