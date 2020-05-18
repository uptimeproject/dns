<?php declare(strict_types = 1);

namespace UptimeProject\Dns\Tests;

use PHPUnit\Framework\TestCase;
use UptimeProject\Dns\DnsResolver;
use UptimeProject\Dns\Handlers\MockHandler;
use UptimeProject\Dns\Resources\Record;
use UptimeProject\Dns\Resources\RecordSet;

class TrimPeriodsTest extends TestCase
{
    public function test_trim_periods()
    {
        $dig = new MockHandler();
        $dig->setMockResponse('example.com.		3600	IN	A	104.198.14.52.
');
        $service = new DnsResolver($dig, true);
        $records = $service->resolve('example.com', 'A');
        $this->assertInstanceOf(RecordSet::class, $records);
        $this->assertSame(1, $records->count());

        $this->assertInstanceOf(Record::class, $records[0]);
        $this->assertSame('example.com', $records[0]->getName());
        $this->assertSame('104.198.14.52', $records[0]->getContent());
    }

    public function test_trim_no_periods()
    {
        $dig = new MockHandler();
        $dig->setMockResponse('example.com.		3600	IN	A	104.198.14.52.
');
        $service = new DnsResolver($dig, false);
        $records = $service->resolve('example.com', 'A');
        $this->assertInstanceOf(RecordSet::class, $records);
        $this->assertSame(1, $records->count());

        $this->assertInstanceOf(Record::class, $records[0]);
        $this->assertSame('example.com.', $records[0]->getName());
        $this->assertSame('104.198.14.52.', $records[0]->getContent());
    }
}
