<?php declare(strict_types = 1);

namespace UptimeProject\Dns\Tests;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use UptimeProject\Dns\DnsResolver;
use UptimeProject\Dns\Handlers\MockHandler;
use UptimeProject\Dns\Resources\Record;
use UptimeProject\Dns\Resources\RecordSet;

class TrimPeriodsTest extends TestCase
{
    public function test_trim_periods_default(): void
    {
        $dig = new MockHandler();
        $dig->setMockResponse('example.com.		3600	IN	A	104.198.14.52.
');
        $service = new DnsResolver($dig);
        $records = $service->resolve('example.com', 'A');
        Assert::assertInstanceOf(RecordSet::class, $records);
        Assert::assertSame(1, $records->count());

        Assert::assertInstanceOf(Record::class, $records[0]);
        /** @var Record $record */
        $record = $records[0];
        Assert::assertSame('example.com', $record->getName());
        Assert::assertSame('104.198.14.52', $record->getContent());
    }

    public function test_trim_periods(): void
    {
        $dig = new MockHandler();
        $dig->setMockResponse('example.com.		3600	IN	A	104.198.14.52.
');
        $service = new DnsResolver($dig, true);
        $records = $service->resolve('example.com', 'A');
        Assert::assertInstanceOf(RecordSet::class, $records);
        Assert::assertSame(1, $records->count());

        Assert::assertInstanceOf(Record::class, $records[0]);
        /** @var Record $record */
        $record = $records[0];
        Assert::assertSame('example.com', $record->getName());
        Assert::assertSame('104.198.14.52', $record->getContent());
    }

    public function test_trim_no_periods(): void
    {
        $dig = new MockHandler();
        $dig->setMockResponse('example.com.		3600	IN	A	104.198.14.52.
');
        $service = new DnsResolver($dig, false);
        $records = $service->resolve('example.com', 'A');
        Assert::assertInstanceOf(RecordSet::class, $records);
        Assert::assertSame(1, $records->count());

        Assert::assertInstanceOf(Record::class, $records[0]);
        /** @var Record $record */
        $record = $records[0];
        Assert::assertSame('example.com', $record->getName());
        Assert::assertSame('104.198.14.52', $record->getContent());
    }
}
