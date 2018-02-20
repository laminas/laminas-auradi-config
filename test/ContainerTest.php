<?php
/**
 * @see       https://github.com/zendframework/zend-auradi-config for the canonical source repository
 * @copyright Copyright (c) 2017 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-auradi-config/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace ZendTest\AuraDi\Config;

use Psr\Container\ContainerInterface;
use Zend\AuraDi\Config\Config;
use Zend\AuraDi\Config\ContainerFactory;
use Zend\ContainerTest\AliasTestTrait;
use Zend\ContainerTest\FactoryTestTrait;
use Zend\ContainerTest\InvokableTestTrait;

class ContainerTest extends \Zend\ContainerTest\ContainerTest
{
    use AliasTestTrait;
    use FactoryTestTrait;
    use InvokableTestTrait;

    protected function createContainer(array $config) : ContainerInterface
    {
        $factory = new ContainerFactory();

        return $factory(new Config(['dependencies' => $config]));
    }
}
