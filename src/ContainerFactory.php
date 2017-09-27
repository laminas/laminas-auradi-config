<?php

namespace Zend\AuraDi\Config;

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
