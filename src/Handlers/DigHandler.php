<?php declare(strict_types = 1);

namespace UptimeProject\Dns\Handlers;

use Spatie\Dns\Dns;
use Spatie\Dns\Exceptions\CouldNotFetchDns as SpatieCouldNotFetchDns;
use UptimeProject\Dns\Exceptions\CouldNotFetchDns;
use UptimeProject\Dns\Exceptions\InvalidArgument;

class DigHandler implements ResolveHandlerInterface
{
    /**
     * @var string[]
     */
    protected $allowedTypes = [
        'A',
        'AAAA',
        'CNAME',
        'NS',
        'SOA',
        'MX',
        'SRV',
        'TXT',
        'DNSKEY',
        'CAA',
        'NAPTR',
    ];

    /* @throws CouldNotFetchDns */
    public function resolve(string $host, string $recordType, ?string $nameServer = null): ?string
    {
        $this->assertHostIsValid($host);
        $this->assertRecordTypeIsValid($recordType);

        $nameServer = $nameServer ?? '';

        $resolver = new Dns($host, $nameServer);
        try {
            $records = $resolver->getRecords($recordType);
            return ($records === '') ? null : $records;
        } catch (SpatieCouldNotFetchDns $e) {
            throw new CouldNotFetchDns($e->getMessage());
        }
    }

    private function assertHostIsValid(string $host): void
    {
        if (! filter_var($host, FILTER_VALIDATE_DOMAIN, ['flags' => FILTER_FLAG_HOSTNAME])) {
            throw new InvalidArgument("Host '$host' is invalid.");
        }
    }

    private function assertRecordTypeIsValid(string $recordType): void
    {
        if (! in_array($recordType, $this->allowedTypes)) {
            throw new InvalidArgument("Record type '$recordType' is allowed.");
        }
    }
}
