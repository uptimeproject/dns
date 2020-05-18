# uptimeproject/dns

This tool is meant as a replacement for `dns_get_record` with some extra functionality.

Mainly, it adds the ability to specify a custom nameserver through which to resolve records.

## How to use

```bash
composer require uptimeproject/dns
```

```php
$resolver = new \UptimeProject\Dns\DnsResolver;

$records = $resolver->resolve('example.com', 'A', 'ns.example.com');

foreach ($records as $record) {
    echo "The {$record->getType()} record for {$record->getName()} resolves\n";
    echo "to {$record->getContent()} with a TTL of {$record->getTTL()} seconds.\n";
}
```

Specifying the nameserver is optional.

