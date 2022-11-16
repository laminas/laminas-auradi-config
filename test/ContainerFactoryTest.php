<?php

declare(strict_types=1);

namespace LaminasTest\AuraDi\Config;

use Aura\Di\ContainerConfigInterface;
use Laminas\AuraDi\Config\ContainerFactory;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class ContainerFactoryTest extends TestCase
{
    private ContainerFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = new ContainerFactory();
    }

    public function testFactoryCreatesPsr11Container(): void
    {
        $factory = $this->factory;
        $config  = $this->createMock(ContainerConfigInterface::class);

        $container = $factory($config);

        self::assertInstanceOf(ContainerInterface::class, $container);
    }
}
