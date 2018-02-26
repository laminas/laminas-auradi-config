# zend-auradi-config

[![Build Status](https://secure.travis-ci.org/zendframework/zend-auradi-config.svg?branch=master)](https://secure.travis-ci.org/zendframework/zend-auradi-config)
[![Coverage Status](https://coveralls.io/repos/github/zendframework/zend-auradi-config/badge.svg?branch=master)](https://coveralls.io/github/zendframework/zend-auradi-config?branch=master)

This library provides utilities to configure
a [PSR-11](http://www.php-fig.org/psr/psr-11/) compatible
[Aura.Di container](https://github.com/auraphp/Aura.Di)
using zend-servicemanager configuration.

## Installation

Run the following to install this library:

```bash
$ composer require zendframework/zend-auradi-config
```

## Configuration

To get a configured Aura.Di container, do the following:

```php
<?php
use Zend\AuraDi\Config\Config;
use Zend\AuraDi\Config\ContainerFactory;

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
  The key and service name usually are the same; if they are not, the key is
  treated as an alias.
- `factories`: an associative array that maps a service name to a factory class
  name, or any callable. Factory classes must be instantiable without arguments,
  and callable once instantiated (i.e., implement the `__invoke()` method).
- `aliases`: an associative array that maps an alias to a service name (or
  another alias).
- `delegators`: an associative array that maps service names to lists of
  delegator factory keys, see the
  [Expressive delegators documentation](https://docs.zendframework.com/zend-servicemanager/delegators/)
  for more details.

> Please note, that the whole configuration is available in the `$container`
> on `config` key:
>
> ```php
> $config = $container->get('config');
> ```

## Using with Expressive

Replace the contents of `config/container.php` with the following:

```php
<?php

use Zend\AuraDi\Config\Config;
use Zend\AuraDi\Config\ContainerFactory;

$config  = require __DIR__ . '/config.php';
$factory = new ContainerFactory();

return $factory(new Config($config));
```
