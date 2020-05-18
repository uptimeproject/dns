<?php declare(strict_types = 1);

namespace UptimeProject\Dns\Tests;

use PHPUnit\Framework\TestCase;
use UptimeProject\Dns\Exceptions\CouldNotFetchDns;
use UptimeProject\Dns\Exceptions\InvalidArgument;
use UptimeProject\Dns\Handlers\DigHandler;

class DigHandlerTest extends TestCase
{
    public function test_resolve()
    {
        $dig = new DigHandler();
        $response = $dig->resolve('uptimeproject.io', 'A');
        $this->assertIsString($response);
        $this->assertNotNull($response);
        $this->assertNotSame('', $response);
    }

    public function test_resolve_own_nameserver()
    {
        $dig = new DigHandler();
        $response = $dig->resolve('example.com', 'A', 'a.iana-servers.net');
        $this->assertIsString($response);
        $this->assertNotNull($response);
        $this->assertNotSame('', $response);
    }

    public function test_resolve_invalid_host()
    {
        $dig = new DigHandler();
        $this->expectException(InvalidArgument::class);
        $dig->resolve('--yikes', 'A');
    }

    public function test_resolve_invalid_record()
    {
        $dig = new DigHandler();
        $this->expectException(InvalidArgument::class);
        $dig->resolve('example.com', 'B');
    }

    public function test_resolve_crash_dig()
    {
        $dig = new DigHandler();
        $this->expectException(CouldNotFetchDns::class);
        $dig->resolve('example.com', 'A', 'ns.example.com --this-is-invalid');
    }
}
