<?php

declare(strict_types=1);

namespace LaminasTest\AuraDi\Config;

use ArrayObject;
use Aura\Di\ContainerBuilder;
use Laminas\AuraDi\Config\Config;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    /** @var ContainerBuilder */
    private $builder;

    protected function setUp(): void
    {
        parent::setUp();

        $this->builder = new ContainerBuilder();
    }

    public function testInjectConfiguration(): void
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
