<?php declare(strict_types = 1);

namespace UptimeProject\Dns\Tests;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use UptimeProject\Dns\DnsResolver;
use UptimeProject\Dns\Handlers\MockHandler;
use UptimeProject\Dns\Resources\Record;
use UptimeProject\Dns\Resources\RecordSet;

class EndToEndTest extends TestCase
{
    public function test_mx_records(): void
    {
        $dig = new MockHandler();
        $dig->setMockResponse('example.com.		3600 IN	MX 10 primary.mail.example.com.
example.com.		3600 IN	MX 20 fallback.mail.example.com.

');
        $service = new DnsResolver($dig);
        $records = $service->resolve('example.com', 'MX');
        Assert::assertInstanceOf(RecordSet::class, $records);
        Assert::assertSame(2, $records->count());

        Assert::assertInstanceOf(Record::class, $records[0]);
        /** @var Record $record1 */
        $record1 = $records[0];
        Assert::assertSame('example.com', $record1->getName());
        Assert::assertSame(3600, $record1->getTTL());
        Assert::assertSame('IN', $record1->getClass());
        Assert::assertSame('MX', $record1->getType());
        Assert::assertSame(10, $record1->getPriority());
        Assert::assertSame('primary.mail.example.com', $record1->getContent());

        Assert::assertInstanceOf(Record::class, $records[1]);
        /** @var Record $record2 */
        $record2 = $records[1];
        Assert::assertSame('example.com', $record2->getName());
        Assert::assertSame(3600, $record2->getTTL());
        Assert::assertSame('IN', $record2->getClass());
        Assert::assertSame('MX', $record2->getType());
        Assert::assertSame(20, $record2->getPriority());
        Assert::assertSame('fallback.mail.example.com', $record2->getContent());
    }

    public function test_a_records(): void
    {
        $dig = new MockHandler();
        $dig->setMockResponse('example.com.		3600	IN	A	104.198.14.52.
');
        $service = new DnsResolver($dig);
        $records = $service->resolve('example.com', 'A');
        Assert::assertInstanceOf(RecordSet::class, $records);
        Assert::assertSame(1, $records->count());

        Assert::assertInstanceOf(Record::class, $records[0]);
        /** @var Record $record1 */
        $record1 = $records[0];
        Assert::assertSame('example.com', $record1->getName());
        Assert::assertSame(3600, $record1->getTTL());
        Assert::assertSame('IN', $record1->getClass());
        Assert::assertSame('A', $record1->getType());
        Assert::assertSame(null, $record1->getPriority());
        Assert::assertSame('104.198.14.52', $record1->getContent());
    }

    public function test_aaaa_records(): void
    {
        $dig = new MockHandler();
        $dig->setMockResponse('example.com.		78416	IN	AAAA	2606:2800:220:1:248:1893:25c8:1946
');
        $service = new DnsResolver($dig);
        $records = $service->resolve('example.com', 'AAAA');
        Assert::assertInstanceOf(RecordSet::class, $records);
        Assert::assertSame(1, $records->count());

        Assert::assertInstanceOf(Record::class, $records[0]);
        /** @var Record $record1 */
        $record1 = $records[0];
        Assert::assertSame('example.com', $record1->getName());
        Assert::assertSame(78416, $record1->getTTL());
        Assert::assertSame('IN', $record1->getClass());
        Assert::assertSame('AAAA', $record1->getType());
        Assert::assertSame(null, $record1->getPriority());
        Assert::assertSame('2606:2800:220:1:248:1893:25c8:1946', $record1->getContent());
    }

    public function test_aaaa_records_lowercase(): void
    {
        $dig = new MockHandler();
        $dig->setMockResponse('example.com.		78416	in	aaaa	2606:2800:220:1:248:1893:25c8:1946
');
        $service = new DnsResolver($dig);
        $records = $service->resolve('example.com', 'AAAA');
        Assert::assertInstanceOf(RecordSet::class, $records);
        Assert::assertSame(1, $records->count());

        Assert::assertInstanceOf(Record::class, $records[0]);
        /** @var Record $record1 */
        $record1 = $records[0];
        Assert::assertSame('example.com', $record1->getName());
        Assert::assertSame(78416, $record1->getTTL());
        Assert::assertSame('IN', $record1->getClass());
        Assert::assertSame('AAAA', $record1->getType());
        Assert::assertSame(null, $record1->getPriority());
        Assert::assertSame('2606:2800:220:1:248:1893:25c8:1946', $record1->getContent());
    }

    public function test_no_records(): void
    {
        $dig = new MockHandler();
        $dig->setMockResponse('');
        $service = new DnsResolver($dig);
        $records = $service->resolve('example.com', 'A');
        Assert::assertInstanceOf(RecordSet::class, $records);
        Assert::assertSame(0, $records->count());
    }

    public function test_not_resolved(): void
    {
        $dig = new MockHandler();
        $dig->setMockResponse(null);
        $service = new DnsResolver($dig);
        $records = $service->resolve('example.com', 'A');
        Assert::assertInstanceOf(RecordSet::class, $records);
        Assert::assertSame(0, $records->count());
    }

    public function test_txt_records(): void
    {
        $dig = new MockHandler();
        $dig->setMockResponse('example.com.		300	IN	TXT	"v=spf1 include:_spf4.example.com include:_spf6.example.com ~all"
');
        $service = new DnsResolver($dig);
        $records = $service->resolve('example.com', 'TXT');
        Assert::assertInstanceOf(RecordSet::class, $records);
        Assert::assertSame(1, $records->count());

        Assert::assertInstanceOf(Record::class, $records[0]);
        /** @var Record $record1 */
        $record1 = $records[0];
        Assert::assertSame('example.com', $record1->getName());
        Assert::assertSame(300, $record1->getTTL());
        Assert::assertSame('IN', $record1->getClass());
        Assert::assertSame('TXT', $record1->getType());
        Assert::assertSame(null, $record1->getPriority());
        Assert::assertSame('v=spf1 include:_spf4.example.com include:_spf6.example.com ~all', $record1->getContent());
    }
}
