<?php

/**
 * @see       https://github.com/laminas/laminas-auradi-config for the canonical source repository
 * @copyright https://github.com/laminas/laminas-auradi-config/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-auradi-config/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\AuraDi\Config;

use Aura\Di\ContainerBuilder;
use Aura\Di\ContainerConfigInterface;

class ContainerFactory
{
    public function __invoke(ContainerConfigInterface $config)
    {
        $builder = new ContainerBuilder();

        return $builder->newConfiguredInstance([$config]);
    }
}
