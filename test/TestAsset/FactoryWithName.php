<?php
/**
 * @see       https://github.com/zendframework/zend-auradi-config for the canonical source repository
 * @copyright Copyright (c) 2017 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-auradi-config/blob/master/LICENSE.md New BSD License
 */

namespace ZendTest\AuraDi\Config\TestAsset;

class FactoryWithName
{
    public function __invoke()
    {
        return func_get_args();
    }
}
