<?php

/**
 * @see       https://github.com/laminas/laminas-auradi-config for the canonical source repository
 * @copyright https://github.com/laminas/laminas-auradi-config/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-auradi-config/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\AuraDi\Config\TestAsset;

class Service
{
    public $injected = [];

    public function __invoke($a = null)
    {
        return $a;
    }

    public function inject($name)
    {
        $this->injected[] = $name;
    }
}
