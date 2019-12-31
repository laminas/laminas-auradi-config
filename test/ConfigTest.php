<?php

namespace LaminasTest\AuraDi\Config;

use Aura\Di\ContainerBuilder;
use Laminas\AuraDi\Config\Config;
use PHPUnit\Framework\TestCase;

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
        self::assertInstanceOf(\ArrayObject::class, $container->get('config'));
        self::assertSame($config, $container->get('config')->getArrayCopy());
    }

    public function testInjectService()
    {
        $myService = new TestAsset\Service();
        $dependencies = [
            'services' => [
                'foo-bar' => $myService,
            ],
        ];

        $container = $this->builder->newConfiguredInstance([new Config(['dependencies' => $dependencies])]);

        self::assertTrue($container->has('foo-bar'));
        self::assertSame($myService, $container->get('foo-bar'));
    }

    public function testInjectServiceFactory()
    {
        $factory = new TestAsset\Factory();

        $dependencies = [
            'services'  => [
                'factory' => $factory,
            ],
            'factories' => [
                'foo-bar' => 'factory',
            ],
        ];

        $container = $this->builder->newConfiguredInstance([new Config(['dependencies' => $dependencies])]);

        self::assertTrue($container->has('factory'));
        self::assertTrue($container->has('foo-bar'));
        self::assertInstanceOf(TestAsset\Service::class, $container->get('foo-bar'));
    }

    public function testInjectInvokableFactory()
    {
        $dependencies = [
            'factories' => [
                'foo-bar' => TestAsset\Factory::class,
            ],
        ];

        $container = $this->builder->newConfiguredInstance([new Config(['dependencies' => $dependencies])]);

        self::assertTrue($container->has('foo-bar'));
        self::assertInstanceOf(TestAsset\Service::class, $container->get('foo-bar'));
    }

    public function testInjectInvokable()
    {
        $dependencies = [
            'invokables' => [
                'foo-bar' => TestAsset\Service::class,
            ],
        ];

        $container = $this->builder->newConfiguredInstance([new Config(['dependencies' => $dependencies])]);

        self::assertTrue($container->has('foo-bar'));
        self::assertInstanceOf(TestAsset\Service::class, $container->get('foo-bar'));
    }

    public function testInjectAlias()
    {
        $myService = new TestAsset\Service();

        $dependencies = [
            'services' => [
                'foo-bar' => $myService,
            ],
            'aliases'  => [
                'alias' => 'foo-bar',
            ],
        ];

        $container = $this->builder->newConfiguredInstance([new Config(['dependencies' => $dependencies])]);

        self::assertTrue($container->has('alias'));
        self::assertSame($myService, $container->get('alias'));
    }

    public function testInjectDelegatorForInvokable()
    {
        $dependencies = [
            'invokables' => [
                'foo-bar' => TestAsset\Service::class,
            ],
            'delegators' => [
                'foo-bar' => [
                    TestAsset\DelegatorFactory::class,
                ],
            ],
        ];

        $container = $this->builder->newConfiguredInstance([new Config(['dependencies' => $dependencies])]);

        self::assertTrue($container->has('foo-bar'));
        $delegator = $container->get('foo-bar');
        self::assertInstanceOf(TestAsset\Delegator::class, $delegator);
        $callback = $delegator->callback;
        self::assertInstanceOf(TestAsset\Service::class, $callback());
    }

    public function testInjectDelegatorForService()
    {
        $myService = new TestAsset\Service();
        $dependencies = [
            'services' => [
                'foo-bar' => $myService,
            ],
            'delegators' => [
                'foo-bar' => [
                    TestAsset\DelegatorFactory::class,
                ],
            ],
        ];

        $container = $this->builder->newConfiguredInstance([new Config(['dependencies' => $dependencies])]);

        self::assertTrue($container->has('foo-bar'));
        $delegator = $container->get('foo-bar');
        self::assertInstanceOf(TestAsset\Delegator::class, $delegator);
        $callback = $delegator->callback;
        self::assertSame($myService, $callback());
    }

    public function testInjectDelegatorForFactory()
    {
        $dependencies = [
            'factories' => [
                'foo-bar' => TestAsset\Factory::class,
            ],
            'delegators' => [
                'foo-bar' => [
                    TestAsset\DelegatorFactory::class,
                ],
            ],
        ];

        $container = $this->builder->newConfiguredInstance([new Config(['dependencies' => $dependencies])]);

        self::assertTrue($container->has('foo-bar'));
        $delegator = $container->get('foo-bar');
        self::assertInstanceOf(TestAsset\Delegator::class, $delegator);
        $callback = $delegator->callback;
        self::assertInstanceOf(TestAsset\Service::class, $callback());
    }

    public function testInjectMultipleDelegators()
    {
        $dependencies = [
            'invokables' => [
                'foo-bar' => TestAsset\Service::class,
            ],
            'delegators' => [
                'foo-bar' => [
                    TestAsset\Delegator1Factory::class,
                    TestAsset\Delegator2Factory::class,
                ],
            ],
        ];

        $container = $this->builder->newConfiguredInstance([new Config(['dependencies' => $dependencies])]);

        self::assertTrue($container->has('foo-bar'));
        $service = $container->get('foo-bar');
        self::assertInstanceOf(TestAsset\Service::class, $service);
        self::assertEquals(
            [
                TestAsset\Delegator1Factory::class,
                TestAsset\Delegator2Factory::class,
            ],
            $service->injected
        );
    }
}
