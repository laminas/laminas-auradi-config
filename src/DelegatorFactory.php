<?php

/**
 * @see       https://github.com/laminas/laminas-auradi-config for the canonical source repository
 * @copyright https://github.com/laminas/laminas-auradi-config/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-auradi-config/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Laminas\AuraDi\Config;

use Aura\Di\Container;
use Aura\Di\Exception\ServiceNotFound;

use function class_exists;
use function get_class;
use function gettype;
use function is_callable;
use function is_object;
use function is_string;
use function sprintf;

/**
 * Aura.Di-compatible delegator factory.
 *
 * Map an instance of this:
 *
 * <code>
 * $container->set(
 *     $serviceName,
 *     $container->lazyGetCall(
 *         $delegatorFactoryInstance,
 *         'build',
 *         $container,
 *         $serviceName
 *     )
 * )
 * </code>
 *
 * Instances receive the list of delegator factory names or instances, and a
 * closure that can create the initial service instance to pass to the first
 * delegator.
 */
class DelegatorFactory
{
    /**
     * @var array Either delegator factory names or instances.
     * @psalm-var list<class-string|callable>
     */
    private $delegators;

    /** @var callable */
    private $factory;

    /**
     * @param array $delegators Array of delegator factory names or instances.
     * @param callable $factory Callable that can return the initial instance.
     * @psalm-param list<class-string|callable> $delegators
     */
    public function __construct(array $delegators, callable $factory)
    {
        $this->delegators = $delegators;
        $this->factory    = $factory;
    }

    /**
     * Build the instance, invoking each delegator with the result of the previous.
     *
     * @return mixed
     * @throws ServiceNotFound
     */
    public function build(Container $container, string $serviceName)
    {
        $callback = $this->factory;

        foreach ($this->delegators as $delegator) {
            if (is_string($delegator)) {
                $delegator = $this->createDelegatorFromClassName($delegator);
            }

            if (! is_callable($delegator)) {
                throw new ServiceNotFound(sprintf(
                    'Delegator of type %s is not callable',
                    is_object($delegator) ? get_class($delegator) : gettype($delegator)
                ));
            }

            $instance = $delegator($container, $serviceName, $callback);
            $callback = static function () use ($instance) {
                return $instance;
            };
        }

        return $instance ?? $callback();
    }

    private function createDelegatorFromClassName(string $delegatorName): object
    {
        if (! class_exists($delegatorName)) {
            throw new ServiceNotFound(sprintf(
                'Delegator class %s does not exist',
                $delegatorName
            ));
        }

        /** @psalm-var class-string $delegatorName */
        return new $delegatorName();
    }
}
