<?php
/**
 * @see       https://github.com/zendframework/zend-auradi-config for the canonical source repository
 * @copyright Copyright (c) 2017 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/zend-auradi-config/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Zend\AuraDi\Config;

use Aura\Di\Container;

use function array_reduce;
use function is_callable;

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
        $this->factory    = $factory;
    }

    /**
     * Build the instance, invoking each delegator with the result of the previous.
     *
     * @return mixed
     */
    public function build(Container $container, string $serviceName)
    {
        $factory = $this->factory;
        return array_reduce(
            $this->delegators,
            function ($instance, $delegatorName) use ($serviceName, $container) {
                $delegator = is_callable($delegatorName) ? $delegatorName : new $delegatorName();
                return $delegator($container, $serviceName, function () use ($instance) {
                    return $instance;
                });
            },
            $factory()
        );
    }
}
