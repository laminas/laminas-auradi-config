<?php

declare(strict_types=1);

namespace LaminasTest\AuraDi\Config;

use Laminas\AuraDi\Config\Config;
use Laminas\AuraDi\Config\ContainerFactory;
use Laminas\ContainerConfigTest\AbstractMezzioContainerConfigTest;
use Psr\Container\ContainerInterface;

class ContainerTest extends AbstractMezzioContainerConfigTest
{
    protected function createContainer(array $config): ContainerInterface
    {
        $factory = new ContainerFactory();

        return $factory(new Config(['dependencies' => $config]));
    }
}
