<?php declare(strict_types = 1);

namespace UptimeProject\Dns;

use UptimeProject\Dns\Resolver\Dig;
use UptimeProject\Dns\Resolver\ResolverInterface;
use UptimeProject\Dns\Resources\RecordSet;

class DnsResolver
{
    /**
     * @var ResolverInterface
     */
    private $resolver;

    /**
     * @var bool
     */
    private $trimTrailingPeriods;

    public function __construct(?ResolverInterface $resolver = null, $trimTrailingPeriods = true)
    {
        $this->resolver = $resolver ?? new Dig();
        $this->trimTrailingPeriods = $trimTrailingPeriods;
    }

    public function resolve(string $host, string $recordType, ?string $nameServer = null) : RecordSet
    {
        $response = $this->resolver->resolve($host, $recordType, $nameServer);
        return RecordSet::fromString($response, $this->trimTrailingPeriods);
    }
}
