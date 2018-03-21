<?php
/**
 * @see       https://github.com/zendframework/zend-auradi-config for the canonical source repository
 * @copyright Copyright (c) 2017-2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-auradi-config/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace ZendTest\AuraDi\Config;

use Psr\Container\ContainerInterface;
use Zend\AuraDi\Config\Config;
use Zend\AuraDi\Config\ContainerFactory;
use Zend\ContainerConfigTest\AllTestTrait;

class ContainerTest extends \Zend\ContainerConfigTest\ContainerTest
{
    use AllTestTrait;

    protected function createContainer(array $config) : ContainerInterface
    {
        $factory = new ContainerFactory();

        return $factory(new Config(['dependencies' => $config]));
    }
}
