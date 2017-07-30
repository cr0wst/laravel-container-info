<?php

namespace Smcrow\BindingUtilities\Services;

use Illuminate\Container\Container;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
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
     * @param bool $includeIlluminate Whether or not to include the Illuminate bindings.
     * @return array of arrays containing the 'concrete' and 'abstract' classes for each bound pair.
     */
    public function getBindingList($includeIlluminate = true)
    {
        // Get the bindings off the injected container.
        $bindings = $this->container->getBindings();

        $foundBindings = [];

        // Use reflection on each of the binding closures to pull the static $concrete and $abstract variables.
        foreach($bindings as $binding) {
            $reflection = new ReflectionFunction($binding["concrete"]);

            $staticVariables = $reflection->getStaticVariables();

            // Check to make sure the Closure has a concrete and abstract.  Also only include Illuminate bindings
            // if the boolean is true.
            if (array_has($staticVariables, ['concrete', 'abstract'])
                && ($includeIlluminate || strpos($staticVariables['abstract'], 'Illuminate\\') === false)) {
                array_push($foundBindings, array_intersect_key($staticVariables, array_flip(['concrete', 'abstract'])));
            }
        }

        return $foundBindings;
    }

    /**
     * Get a list of the bindings and the files in which they are referenced.
     * @param bool $includeIlluminate Whether or not to include the Illuminate bindings.
     * @return array of arrays containing the 'abstract' and an array of files which reference it.
     */
    public function getUsageList($includeIlluminate = true)
    {
        // First get an array of all of the abstracts
        $abstracts = array_column($this->getBindingList($includeIlluminate), 'abstract');

        // Generate an array of bindings and their location.
        // It's preferable to search each file for each binding rather than for each binding to search each file.
        // This way we are only searching through the file system once.
        $bindingsAndLocation = [];
        $directory = new RecursiveDirectoryIterator(base_path());
        foreach (new RecursiveIteratorIterator($directory) as $file) {
            if ($file->getExtension() === 'php') {
                $content = file_get_contents($file->getPathname());
                // search file for each abstract
                foreach ($abstracts as $abstract) {
                    if (strpos($content, $abstract)) {
                        // If we found a reference we will add the file to the array list with the abstract key
                        if (!array_key_exists($abstract, $bindingsAndLocation)) {
                            // Initialize the array with the abstract key if it doesn't exist.
                            $bindingsAndLocation[$abstract] = [];
                        }

                        array_push($bindingsAndLocation[$abstract], str_replace(base_path().'\\', '', $file->getPathName()));
                    }
                }
            }
        }

        return $bindingsAndLocation;
    }
}