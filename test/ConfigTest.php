<?php
/**
 * @see       https://github.com/zendframework/zend-auradi-config for the canonical source repository
 * @copyright Copyright (c) 2017-2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-auradi-config/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace ZendTest\AuraDi\Config;

use ArrayObject;
use Aura\Di\ContainerBuilder;
use PHPUnit\Framework\TestCase;
use Zend\AuraDi\Config\Config;

class ConfigTest extends TestCase
{
    /** @var ContainerBuilder */
    private $builder;

    protected function setUp()
    {
        parent::setUp();

        $this->builder = new ContainerBuilder();
    }

    public function testInjectConfiguration()
    {
        $config = [
            'foo' => 'bar',
        ];

        $container = $this->builder->newConfiguredInstance([new Config($config)]);

        self::assertTrue($container->has('config'));
        self::assertInstanceOf(ArrayObject::class, $container->get('config'));
        self::assertSame($config, $container->get('config')->getArrayCopy());
    }
}
