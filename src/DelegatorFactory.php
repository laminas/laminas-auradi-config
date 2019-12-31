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
use function is_callable;
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
     */
    private $delegators;

    /**
     * @var callable
     */
    private $factory;

    /**
     * @param array $delegators Array of delegator factory names or instances.
     * @param callable $factory Callable that can return the initial instance.
     */
    public function __construct(array $delegators, callable $factory)
    {
        $this->delegators = $delegators;
        $this->factory = $factory;
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

        foreach ($this->delegators as $delegatorName) {
            if (! class_exists($delegatorName)) {
                throw new ServiceNotFound(sprintf(
                    'Delegator class %s does not exist',
                    $delegatorName
                ));
            }

            $delegator = new $delegatorName();

            if (! is_callable($delegator)) {
                throw new ServiceNotFound(sprintf(
                    'Delegator class %s is not callable',
                    $delegatorName
                ));
            }

            $instance = $delegator($container, $serviceName, $callback);
            $callback = function () use ($instance) {
                return $instance;
            };
        }

        return $instance ?? $callback();
    }
}
