<?php

declare(strict_types=1);

namespace Laminas\AuraDi\Config;

use ArrayObject;
use Aura\Di\Container;
use Aura\Di\ContainerConfigInterface;
use Aura\Di\Exception\ServiceNotFound;
use Psr\Container\ContainerInterface;

use function array_search;
use function class_exists;
use function is_array;
use function is_callable;
use function is_string;
use function sprintf;
use function var_export;

/**
 * Configuration for the Aura.Di container.
 *
 * This class provides functionality for the following service types:
 *
 * - Aliases
 * - Delegators
 * - Factories
 * - Invokable classes
 * - Services (known instances)
 */
class Config implements ContainerConfigInterface
{
    /** @var array */
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Configure the container
     *
     * - Adds the 'config' service.
     * - If delegators are defined, maps the service to lazyGetCall an
     *   MezzioAuraDelegatorFactory::build invocation using the configured
     *   delegator and whatever factory was responsible for it.
     * - If factories are defined, maps each factory class as a lazily
     *   instantiable service, and the service to lazyGetCall the factory to
     *   create the instance.
     * - If invokables are defined, maps each to lazyNew the target.
     * - If aliases are defined, maps each to lazyGet the target.
     */
    public function define(Container $di): void
    {
        // Convert config to an object and inject it
        $di->set('config', new ArrayObject($this->config, ArrayObject::ARRAY_AS_PROPS));

        if (
            empty($this->config['dependencies'])
            || ! is_array($this->config['dependencies'])
        ) {
            return;
        }

        $dependencies = $this->config['dependencies'];

        // Inject delegator factories
        // This is done early because Aura.Di does not allow modification of a
        // service after creation. As such, we need to create custom factories
        // for each service with delegators.
        if (
            isset($dependencies['delegators'])
            && is_array($dependencies['delegators'])
        ) {
            $dependencies = $this->marshalDelegators($di, $dependencies);
        }

        // Inject services
        if (
            isset($dependencies['services'])
            && is_array($dependencies['services'])
        ) {
            foreach ($dependencies['services'] as $name => $service) {
                $di->set($name, $service);
            }
        }

        // Inject factories
        if (
            isset($dependencies['factories'])
            && is_array($dependencies['factories'])
        ) {
            foreach ($dependencies['factories'] as $service => $factory) {
                if (is_callable($factory)) {
                    $di->set($service, $di->lazy($factory, $di, $service));
                    continue;
                }

                $di->set(
                    $service,
                    $di->lazy(static function (ContainerInterface $di, string $service) use ($factory) {
                        if (! is_string($factory) || ! class_exists($factory)) {
                            throw new ServiceNotFound(sprintf(
                                'Service %s cannot be initialized by factory %s',
                                $service,
                                is_string($factory) ? $factory : var_export($factory, true)
                            ));
                        }

                        $instance = new $factory();

                        if (! is_callable($instance)) {
                            throw new ServiceNotFound(sprintf(
                                'Service %s cannot be initalized by non invokable factory %s',
                                $service,
                                $factory
                            ));
                        }

                        return $instance($di, $service);
                    }, $di, $service)
                );
            }
        }

        // Inject invokables
        if (
            isset($dependencies['invokables'])
            && is_array($dependencies['invokables'])
        ) {
            foreach ($dependencies['invokables'] as $service => $class) {
                if (is_string($service) && $service !== $class) {
                    $di->set($service, $di->lazyGet($class));
                }

                $di->set($class, $di->lazy(static function () use ($class) {
                    if (! is_string($class) || ! class_exists($class)) {
                        throw new ServiceNotFound(sprintf(
                            'Service %s cannot be created',
                            is_string($class) ? $class : var_export($class, true)
                        ));
                    }

                    return new $class();
                }));
            }
        }

        // Inject aliases
        if (
            isset($dependencies['aliases'])
            && is_array($dependencies['aliases'])
        ) {
            foreach ($dependencies['aliases'] as $alias => $target) {
                if (! is_string($alias)) {
                    continue;
                }

                $di->set($alias, $di->lazyGet($target));
            }
        }
    }

    /**
     * This method is purposely a no-op.
     */
    public function modify(Container $di): void
    {
    }

    /**
     * Marshal all services with delegators.
     *
     * @return array List of dependencies minus any services, factories, or
     *     invokables that match services using delegator factories.
     */
    private function marshalDelegators(Container $di, array $dependencies): array
    {
        foreach ($dependencies['delegators'] as $service => $delegatorNames) {
            $factory = null;

            if (isset($dependencies['factories'][$service])) {
                // Marshal from factory
                $serviceFactory = $dependencies['factories'][$service];
                $factory        = static function () use ($service, $serviceFactory, $di) {
                    if (is_callable($serviceFactory)) {
                        $factory = $serviceFactory;
                    } elseif (is_string($serviceFactory) && ! class_exists($serviceFactory)) {
                        throw new ServiceNotFound(sprintf(
                            'Service %s cannot by initialized by factory %s; factory class does not exist',
                            $service,
                            $serviceFactory
                        ));
                    } else {
                        $factory = new $serviceFactory();
                    }

                    if (! is_callable($factory)) {
                        throw new ServiceNotFound(sprintf(
                            'Service %s cannot by initalized by factory %s; factory is not callable',
                            $service,
                            $serviceFactory
                        ));
                    }

                    return $factory($di, $service);
                };
                unset($dependencies['factories'][$service]);
            }

            if (isset($dependencies['invokables'])) {
                while (false !== ($key = array_search($service, $dependencies['invokables'], true))) {
                    // Marshal from invokable
                    $class   = $dependencies['invokables'][$key];
                    $factory = static function () use ($class): object {
                        if (! is_string($class) || ! class_exists($class)) {
                            throw new ServiceNotFound(sprintf(
                                'Service %s cannot be created',
                                is_string($class) ? $class : var_export($class, true)
                            ));
                        }

                        return new $class();
                    };
                    unset($dependencies['invokables'][$key]);

                    if ($key !== $service) {
                        $dependencies['aliases'][$key] = $service;
                    }
                }
            }

            if (! $factory) {
                continue;
            }

            $delegatorFactory = new DelegatorFactory($delegatorNames, $factory);
            $di->set(
                $service,
                $di->lazy([$delegatorFactory, 'build'], $di, $service)
            );
        }

        return $dependencies;
    }
}
