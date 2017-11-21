# zend-auradi-config

[![Build Status](https://secure.travis-ci.org/zendframework/zend-auradi-config.svg?branch=master)](https://secure.travis-ci.org/zendframework/zend-auradi-config)
[![Coverage Status](https://coveralls.io/repos/github/zendframework/zend-auradi-config/badge.svg?branch=master)](https://coveralls.io/github/zendframework/zend-auradi-config?branch=master)

This library provides utilities to configure
[PSR-11](http://www.php-fig.org/psr/psr-11/)
[Aura.Di container](https://github.com/auraphp/Aura.Di)
using ZendFramework ServiceManager configuration.

## Installation

Run the following to install this library:

```bash
$ composer require zendframework/zend-auradi-config
```

## Configuration

To get configured [PSR-11 Container](http://www.php-fig.org/psr/psr-11/)
Aura.Di Container do the following:

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

- `services`: an associative array that maps a key to a service instance.
- `invokables`: an associative array that map a key to a constructor-less
  services, or services that do not require arguments to the constructor.
- `factories`: an associative array that map a key to a factory name, or any
  callable.
- `aliases`: an associative array that map a key to a service key (or another
  alias).
- `delegators`: an associative array that maps service keys to lists of
  delegator factory keys, see the
  [delegators documentation](https://docs.zendframework.com/zend-servicemanager/delegators/)
  for more details.

> Please note, that the whole configuration is available in the `$container`
> on `config` key:
>
> ```php
> $config = $container->get('config');
> ```

## Using with Expressive

First you have to install the library:
```bash
$ composer require zendframework/zend-auradi-config
```

Then replace contents of `config/container.php` with the following:
```php
<?php

use Zend\AuraDi\Config\Config;
use Zend\AuraDi\Config\ContainerFactory;

$config  = require __DIR__ . '/config.php';
$factory = new ContainerFactory();

return $factory(new Config($config));
```
