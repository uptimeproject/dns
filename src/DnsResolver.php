<?php declare(strict_types = 1);

namespace UptimeProject\Dns;

use UptimeProject\Dns\Handlers\DigHandler;
use UptimeProject\Dns\Handlers\ResolveHandlerInterface;
use UptimeProject\Dns\Resources\RecordSet;

final class DnsResolver
{
    /**
     * @var ResolveHandlerInterface
     */
    private $resolver;

    /**
     * @var bool
     */
    private $trimTrailingPeriods;

    public function __construct(?ResolveHandlerInterface $resolver = null, bool $trimTrailingPeriods = true)
    {
        $this->resolver = $resolver ?? new DigHandler();
        $this->trimTrailingPeriods = $trimTrailingPeriods;
    }

    public function resolve(string $host, string $recordType, ?string $nameServer = null): RecordSet
    {
        $response = $this->resolver->resolve($host, $recordType, $nameServer);
        if ($response === null) {
            return new RecordSet();
        }

        return RecordSet::fromString($response, $this->trimTrailingPeriods);
    }
}
