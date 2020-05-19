<?php declare(strict_types = 1);

namespace UptimeProject\Dns\Resources;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use UptimeProject\Dns\Exceptions\InvalidArgument;

final class RecordSet implements ArrayAccess, IteratorAggregate, Countable
{
    /**
     * @var Record[]
     */
    private $records;

    /**
     * @param Record[] $records
     */
    public function __construct(array $records = [])
    {
        foreach ($records as $record) {
            if (! $record instanceof Record) {
                throw new InvalidArgument('Given record is not an instance of Record.');
            }
        }
        $this->records  = $records;
    }

    public static function fromString(string $data, bool $trimTrailingPeriods = true) : RecordSet
    {
        $lines = array_filter(explode("\n", $data));
        $records = array_map(function ($line) use ($trimTrailingPeriods) {
            return Record::fromString($line, $trimTrailingPeriods);
        }, $lines);

        $records = array_filter($records, function ($record) {
            return $record instanceof Record;
        });

        return new RecordSet($records);
    }

    /**
     * @param int $offset
     */
    public function offsetExists($offset) : bool
    {
        return isset($this->records[$offset]);
    }

    /**
     * @param int $offset
     */
    public function offsetGet($offset) : ?Record
    {
        return isset($this->records[$offset]) ? $this->records[$offset] : null;
    }

    /**
     * @param int|null $offset
     * @param Record   $value
     */
    public function offsetSet($offset, $value) : void
    {
        if (is_null($offset)) {
            $this->records[] = $value;
        } else {
            $this->records[$offset] = $value;
        }
    }

    /**
     * @param int $offset
     */
    public function offsetUnset($offset) : void
    {
        unset($this->records[$offset]);
    }

    /**
     * @return ArrayIterator<Record>
     */
    public function getIterator() : ArrayIterator
    {
        return new ArrayIterator($this->records);
    }

    public function count() : int
    {
        return count($this->records);
    }
}
