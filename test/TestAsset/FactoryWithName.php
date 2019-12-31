<?php

/**
 * @see       https://github.com/laminas/laminas-auradi-config for the canonical source repository
 * @copyright https://github.com/laminas/laminas-auradi-config/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-auradi-config/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\AuraDi\Config\TestAsset;

class FactoryWithName
{
    public function __invoke()
    {
        return func_get_args();
    }
}
