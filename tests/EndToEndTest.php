<?php declare(strict_types = 1);

namespace UptimeProject\Dns\Tests;

use PHPUnit\Framework\TestCase;
use UptimeProject\Dns\DnsResolver;
use UptimeProject\Dns\Handlers\MockHandler;
use UptimeProject\Dns\Resources\Record;
use UptimeProject\Dns\Resources\RecordSet;

class EndToEndTest extends TestCase
{
    public function test_mx_records()
    {
        $dig = new MockHandler();
        $dig->setMockResponse('example.com.		3600 IN	MX 10 primary.mail.example.com.
example.com.		3600 IN	MX 20 fallback.mail.example.com.
');
        $service = new DnsResolver($dig);
        $records = $service->resolve('example.com', 'MX');
        $this->assertInstanceOf(RecordSet::class, $records);
        $this->assertSame(2, $records->count());

        $this->assertInstanceOf(Record::class, $records[0]);
        $this->assertSame('example.com', $records[0]->getName());
        $this->assertSame(3600, $records[0]->getTTL());
        $this->assertSame('IN', $records[0]->getClass());
        $this->assertSame('MX', $records[0]->getType());
        $this->assertSame(10, $records[0]->getPrio());
        $this->assertSame('primary.mail.example.com', $records[0]->getContent());

        $this->assertInstanceOf(Record::class, $records[1]);
        $this->assertSame('example.com', $records[1]->getName());
        $this->assertSame(3600, $records[1]->getTTL());
        $this->assertSame('IN', $records[1]->getClass());
        $this->assertSame('MX', $records[1]->getType());
        $this->assertSame(20, $records[1]->getPrio());
        $this->assertSame('fallback.mail.example.com', $records[1]->getContent());
    }

    public function test_a_records()
    {
        $dig = new MockHandler();
        $dig->setMockResponse('example.com.		3600	IN	A	104.198.14.52.
');
        $service = new DnsResolver($dig);
        $records = $service->resolve('example.com', 'A');
        $this->assertInstanceOf(RecordSet::class, $records);
        $this->assertSame(1, $records->count());

        $this->assertInstanceOf(Record::class, $records[0]);
        $this->assertSame('example.com', $records[0]->getName());
        $this->assertSame(3600, $records[0]->getTTL());
        $this->assertSame('IN', $records[0]->getClass());
        $this->assertSame('A', $records[0]->getType());
        $this->assertSame(null, $records[0]->getPrio());
        $this->assertSame('104.198.14.52', $records[0]->getContent());
    }

    public function test_aaaa_records()
    {
        $dig = new MockHandler();
        $dig->setMockResponse('example.com.		78416	IN	AAAA	2606:2800:220:1:248:1893:25c8:1946
');
        $service = new DnsResolver($dig);
        $records = $service->resolve('example.com', 'AAAA');
        $this->assertInstanceOf(RecordSet::class, $records);
        $this->assertSame(1, $records->count());

        $this->assertInstanceOf(Record::class, $records[0]);
        $this->assertSame('example.com', $records[0]->getName());
        $this->assertSame(78416, $records[0]->getTTL());
        $this->assertSame('IN', $records[0]->getClass());
        $this->assertSame('AAAA', $records[0]->getType());
        $this->assertSame(null, $records[0]->getPrio());
        $this->assertSame('2606:2800:220:1:248:1893:25c8:1946', $records[0]->getContent());
    }

    public function test_no_records()
    {
        $dig = new MockHandler();
        $dig->setMockResponse('');
        $service = new DnsResolver($dig);
        $records = $service->resolve('example.com', 'A');
        $this->assertInstanceOf(RecordSet::class, $records);
        $this->assertSame(0, $records->count());
    }

    public function test_txt_records()
    {
        $dig = new MockHandler();
        $dig->setMockResponse('example.com.		300	IN	TXT	"v=spf1 include:_spf4.example.com include:_spf6.example.com ~all"
');
        $service = new DnsResolver($dig);
        $records = $service->resolve('example.com', 'TXT');
        $this->assertInstanceOf(RecordSet::class, $records);
        $this->assertSame(1, $records->count());

        $this->assertInstanceOf(Record::class, $records[0]);
        $this->assertSame('example.com', $records[0]->getName());
        $this->assertSame(300, $records[0]->getTTL());
        $this->assertSame('IN', $records[0]->getClass());
        $this->assertSame('TXT', $records[0]->getType());
        $this->assertSame(null, $records[0]->getPrio());
        $this->assertSame('"v=spf1 include:_spf4.example.com include:_spf6.example.com ~all"', $records[0]->getContent());
    }
}
