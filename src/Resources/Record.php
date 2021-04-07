<?php declare(strict_types = 1);

namespace UptimeProject\Dns\Resources;

final class Record
{
    private string $name;
    private string $type;
    private string $class;
    private int $ttl;
    private ?int $priority;
    private string $content;

    public function __construct(
        string $name,
        int $ttl,
        string $class,
        string $type,
        ?int $priority,
        string $content
    ) {
        $this->name = $name;
        $this->type = $type;
        $this->class = $class;
        $this->ttl = $ttl;
        $this->priority = $priority;
        $this->content = $content;
    }

    public static function fromString(string $data, bool $trimTrailingPeriods = true): Record
    {
        $bits  = self::explodeLine($data);
        $name  = (string) current($bits);
        $ttl   = (int) next($bits);
        $class = strtoupper(next($bits));
        $type  = strtoupper(next($bits));
        $priority  = $type === 'MX' ? ((int) next($bits)) : null;
        $key = (int) key($bits);
        $content = implode(' ', array_splice($bits, $key + 1));

        if ($trimTrailingPeriods) {
            $name = self::trimTrailingPeriod($name);
            $content = self::trimTrailingPeriod($content);
        }

        return new Record($name, $ttl, $class, $type, $priority, $content);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTTL(): int
    {
        return $this->ttl;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return string[]
     */
    private static function explodeLine(string $line): array
    {
        // Split up the line, filter out empty entries, reset keys.
        $bits = preg_split("/[\t| ]/", $line);
        $bits = $bits !== false ? $bits : [];
        $bits = array_filter($bits);
        return array_values($bits);
    }

    private static function trimTrailingPeriod(string $string): string
    {
        $lastChar = substr($string, -1, 1);
        if ($lastChar === '.') {
            return substr($string, 0, -1);
        }
        return $string;
    }
}
