<?php declare(strict_types = 1);

namespace UptimeProject\Dns;

use UptimeProject\Dns\Resources\RecordSet;

interface DnsResolverInterface
{
    public function resolve(string $host, string $recordType, ?string $nameServer = null): RecordSet;
}
