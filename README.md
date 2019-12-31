# laminas-auradi-config

[![Build Status](https://travis-ci.org/laminas/laminas-auradi-config.svg?branch=master)](https://travis-ci.org/laminas/laminas-auradi-config)
[![Coverage Status](https://coveralls.io/repos/github/laminas/laminas-auradi-config/badge.svg?branch=master)](https://coveralls.io/github/laminas/laminas-auradi-config?branch=master)

This library provides utilities to configure
a [PSR-11](http://www.php-fig.org/psr/psr-11/) compatible
[Aura.Di container](https://github.com/auraphp/Aura.Di)
using laminas-servicemanager configuration.

## Installation

Run the following to install this library:

```bash
$ composer require laminas/laminas-auradi-config
```

## Configuration

To get a configured Aura.Di container, do the following:

```php
<?php
use Laminas\AuraDi\Config\Config;
use Laminas\AuraDi\Config\ContainerFactory;

$factory = new ContainerFactory();

$container = $factory(
    new Config([
        'dependencies' => [
            'services'   => [],
            'invokables' => [],
            'factories'  => [],
            'aliases'    => [],
            'delegators' => [],
        ],
        // ... other configuration
    ])
);
```

The `dependencies` sub associative array can contain the following keys:

- `services`: an associative array that maps a key to a specific service instance.
- `invokables`: an associative array that map a key to a constructor-less
  service; i.e., for services that do not require arguments to the constructor.
  The key and service name may be the same; if they are not, the name is treated
  as an alias.
- `factories`: an associative array that maps a service name to a factory class
  name, or any callable. Factory classes must be instantiable without arguments,
  and callable once instantiated (i.e., implement the `__invoke()` method).
- `aliases`: an associative array that maps an alias to a service name (or
  another alias).
- `delegators`: an associative array that maps service names to lists of
  delegator factory keys, see the
  [Mezzio delegators documentation](https://docs.laminas.dev/laminas-servicemanager/delegators/)
  for more details.

> Please note, that the whole configuration is available in the `$container`
> on `config` key:
>
> ```php
> $config = $container->get('config');
> ```

## Using with Mezzio

Replace the contents of `config/container.php` with the following:

```php
<?php

use Laminas\AuraDi\Config\Config;
use Laminas\AuraDi\Config\ContainerFactory;

$config  = require __DIR__ . '/config.php';
$factory = new ContainerFactory();

return $factory(new Config($config));
```
