<?php

/**
 * @see       https://github.com/laminas/laminas-auradi-config for the canonical source repository
 * @copyright https://github.com/laminas/laminas-auradi-config/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-auradi-config/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace LaminasTest\AuraDi\Config\TestAsset;

use Psr\Container\ContainerInterface;

class DelegatorFactory
{
    public function __invoke(ContainerInterface $container, $name, callable $callback)
    {
        return new Delegator($name, $callback);
    }
}
