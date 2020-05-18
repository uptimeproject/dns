<?php declare(strict_types = 1);

namespace UptimeProject\Dns\Resolver;

use Spatie\Dns\Dns;
use Spatie\Dns\Exceptions\CouldNotFetchDns;

class Dig implements ResolverInterface
{
    public function resolve(string $host, string $recordType, ?string $nameServer = null) : ?string
    {
        $resolver = new Dns($host);
        try {
            $records = $resolver->getRecords($recordType);
            return ($records === '') ? null : $records;
        } catch (CouldNotFetchDns $e) {
            return null;
        }
    }
}
