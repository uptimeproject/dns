<?php declare(strict_types = 1);

namespace UptimeProject\Dns\Tests;

use PHPUnit\Framework\TestCase;
use UptimeProject\Dns\Resolver\Dig;

class DigTest extends TestCase
{
    public function test_resolve()
    {
        $dig = new Dig();
        $response = $dig->resolve('uptimeproject.io', 'A');
        $this->assertIsString($response);
        $this->assertNotNull($response);
        $this->assertNotSame('', $response);
    }

    public function test_resolve_own_nameserver()
    {
        $dig = new Dig();
        $response = $dig->resolve('example.com', 'A', 'a.iana-servers.net');
        $this->assertIsString($response);
        $this->assertNotNull($response);
        $this->assertNotSame('', $response);
    }

    public function test_resolve_fail()
    {
        $dig = new Dig();
        $response = $dig->resolve('--yikes', 'A');
        $this->assertNull($response);
    }
}
