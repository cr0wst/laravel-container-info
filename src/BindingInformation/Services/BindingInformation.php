<?php

namespace Smcrow\ContainerInformation\BindingInformation\Services;

use Illuminate\Container\Container;
use ReflectionFunction;
use Symfony\Component\Finder\Finder;

/**
 * Service for getting binding information from the Container.
 * @package Services
 */
class BindingInformation
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
     *
     * @return array of arrays containing the 'concrete' and 'abstract' classes for each bound pair.
     * @throws \ReflectionException
     */
    public function getBindingList($includeIlluminate = false): array
    {
        // Get the bindings off the injected container.
        $bindings = $this->container->getBindings();

        $foundBindings = [];

        // Use reflection on each of the binding closures to pull the static $concrete and $abstract variables.
        foreach ($bindings as $binding) {
            $reflection = new ReflectionFunction($binding['concrete']);

            $staticVariables = $reflection->getStaticVariables();

            // Check to make sure the Closure has a concrete and abstract.  Also only include Illuminate bindings
            // if the boolean is true.
            if (array_has($staticVariables, ['concrete', 'abstract'])
                && ($includeIlluminate || strpos($staticVariables['abstract'], 'Illuminate\\') === false)) {
                $foundBindings[] = array_intersect_key($staticVariables, array_flip(['concrete', 'abstract']));
            }
        }

        return $foundBindings;
    }

    /**
     * Get a list of the bindings and the files in which they are referenced.
     *
     * @param string $userExcludedDirectories User provided directories that should be excluded.
     * @param bool $includeIlluminate Whether or not to include the Illuminate bindings.
     * @param bool $includeVendor Whether or not to include the vendor folder.
     *
     * @return array of arrays containing the 'abstract' and an array of files which reference it.
     * @throws \ReflectionException
     */
    public function getUsageList(
        string $userExcludedDirectories,
        $includeIlluminate = false,
        $includeVendor = false
    ): array {
        // First get an array of all of the abstracts
        $abstracts = array_column($this->getBindingList($includeIlluminate), 'abstract');

        // Generate an array of bindings and their location.
        // It's preferable to search each file for each binding rather than for each binding to search each file.
        // This way we are only searching through the file system once.
        $bindingsAndLocation = [];

        $finder = app(Finder::class);

        $finder->in(base_path())
            ->name('*.php')
            ->exclude($this->getExcludedDirectories($includeVendor, $userExcludedDirectories));

        foreach ($finder as $file) {
            $content = file_get_contents($file->getPathname());
            // search file for each abstract
            foreach ($abstracts as $abstract) {
                if (strpos($content, $abstract) !== false) {
                    // If we found a reference we will add the file to the array list with the abstract key
                    if (!array_key_exists($abstract, $bindingsAndLocation)) {
                        // Initialize the array with the abstract key if it doesn't exist.
                        $bindingsAndLocation[$abstract] = [];
                    }

                    $bindingsAndLocation[$abstract][] = str_replace(base_path().'\\', '', $file->getPathName());
                }
            }
        }

        return $bindingsAndLocation;
    }

    /**
     * Return an array of directories to exclude in the search.  A user provided directory string will override
     * all opinionated folders excluding node_modules.
     *
     * @param bool $includeVendor Whether vendor should be included.
     * @param string $userExcludedDirectories A comma separated string of directories to exclude.
     *
     * @return array
     */
    private function getExcludedDirectories(bool $includeVendor, string $userExcludedDirectories): array
    {
        // I honestly can't think of a good reason to search in here.
        $excludedDirectories = ['node_modules'];

        // If the user provided a list of directories, we won't be super opinionated and add our own. Except for
        // node_modules.
        if (! empty($userExcludedDirectories)) {
            return array_unique(array_merge(
                // Convert user provided commands to an array
                array_map('trim', explode(',', $userExcludedDirectories)),
                $excludedDirectories
            ));
        }

        if (! $includeVendor) {
            $excludedDirectories[] = 'vendor';
        }

        return $excludedDirectories;
    }
}
