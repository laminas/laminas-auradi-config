<?php

/**
 * @see       https://github.com/laminas/laminas-auradi-config for the canonical source repository
 * @copyright https://github.com/laminas/laminas-auradi-config/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-auradi-config/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Laminas\AuraDi\Config;

use Aura\Di\Container;
use Aura\Di\ContainerBuilder;
use Aura\Di\ContainerConfigInterface;

class ContainerFactory
{
    public function __invoke(ContainerConfigInterface $config): Container
    {
        $builder = new ContainerBuilder();

        return $builder->newConfiguredInstance([$config]);
    }
}
