<?php declare(strict_types = 1);

namespace UptimeProject\Dns\Tests;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use UptimeProject\Dns\Resources\Record;
use UptimeProject\Dns\Resources\RecordSet;

class RecordSetTest extends TestCase
{
    public function test_array_access(): void
    {
        $records = $this->get_records();
        Assert::assertTrue(isset($records[0]));
        Assert::assertFalse(isset($records[2]));
        $records[2] = $records[0];
        Assert::assertTrue(isset($records[2]));
        $records[] = $records[1];
        Assert::assertTrue(isset($records[3]));
        unset($records[3]);
    }

    public function test_iterable(): void
    {
        foreach ($this->get_records() as $key => $value) {
            Assert::assertInstanceOf(Record::class, $value);
            if ($key === 0) {
                Assert::assertSame('A', $value->getType());
            } else {
                Assert::assertSame('AAAA', $value->getType());
            }
        }
    }

    public function test_count(): void
    {
        $records = $this->get_records();

        Assert::assertSame(2, $records->count());
    }

    public function test_construct(): void
    {
        $records = new RecordSet([
            new Record('example.com', 3600, 'IN', 'A', null, '93.184.216.34'),
            new Record('example.com', 3600, 'IN', 'AAAA', null, '2606:2800:220:1:248:1893:25c8:1946'),
        ]);
        Assert::assertSame(2, $records->count());
    }

    public function test_construct_fail(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $records = new RecordSet([
            /** @phpstan-ignore PHPStan.Rules.Deprecations */
            'invalid record',
            new Record('example.com', 3600, 'IN', 'AAAA', null, '2606:2800:220:1:248:1893:25c8:1946'),
        ]);
    }

    private function get_records(): RecordSet
    {
        return new RecordSet([
            new Record('example.com', 3600, 'IN', 'A', null, '93.184.216.34'),
            new Record('example.com', 3600, 'IN', 'AAAA', null, '2606:2800:220:1:248:1893:25c8:1946'),
        ]);
    }
}
