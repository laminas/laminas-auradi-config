<?php
/**
 * @see       https://github.com/zendframework/zend-auradi-config for the canonical source repository
 * @copyright Copyright (c) 2017 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-auradi-config/blob/master/LICENSE.md New BSD License
 */

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
