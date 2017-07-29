<?php

namespace Smcrow\BindingUtilities\Services;

use Illuminate\Container\Container;
use ReflectionFunction;

/**
 * Service for getting binding information from the Container.
 * @package Services
 */
class BindingService
{

    /**
     * @var Container $container
     */
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Get the list of bindings from the Container.
     *
     * @return array of arrays containing the 'concrete' and 'abstract' classes for each bound pair.
     */
    public function getBindingList()
    {
        // Get the bindings off the injected container.
        $bindings = $this->container->getBindings();

        $foundBindings = [];

        // Use reflection on each of the binding closures to pull the static $concrete and $abstract variables.
        foreach($bindings as $binding) {
            $reflection = new ReflectionFunction($binding["concrete"]);

            $staticVariables = $reflection->getStaticVariables();

            if (array_has($staticVariables, ['concrete', 'abstract'])) {
                array_push($foundBindings, array_intersect_key($staticVariables, array_flip(['concrete', 'abstract'])));
            }
        }

        return $foundBindings;
    }

    public function getUsageList()
    {
        // It's preferable to search each file for each binding rather than for each binding to search each file.
        // This way we are only searching through the file system once.
        $abstracts = array_column($this->getBindingList(), 'abstract');
        return $abstracts;
    }

}