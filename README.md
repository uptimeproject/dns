<a href="https://uptimeproject.io" target="_blank"><img src="https://uptimeproject.io/img/logo.png" height="50px" /></a>

![Codecov](https://img.shields.io/codecov/c/github/uptimeproject/dns?style=flat-square)
![GitHub Workflow Status](https://img.shields.io/github/workflow/status/uptimeproject/dns/CI?style=flat-square)
![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/uptimeproject/dns?style=flat-square)
![Packagist PHP Version Support](https://img.shields.io/packagist/v/uptimeproject/dns?style=flat-square)
![Packagist Downloads](https://img.shields.io/packagist/dt/uptimeproject/dns?style=flat-square)

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

As it is built on top of [spatie/dns](https://github.com/spatie/dns) this tool is inherently built on `dig`.
Make sure you have dig installed, otherwise you cannot use this package!

## How to contribute

Feel free to create a PR if you have any ideas for improvements. Or create an issue.

* When adding code, make sure to add tests for it (phpunit).
* Make sure the code adheres to our coding standards (use php-cs-fixer to check/fix). 
* Also make sure PHPStan does not find any bugs.

```bash

vendor/bin/php-cs-fixer fix

vendor/bin/phpstan analyze

vendor/bin/phpunit --coverage-text

```

These tools will also run in GitHub actions on PR's and pushes on master.
