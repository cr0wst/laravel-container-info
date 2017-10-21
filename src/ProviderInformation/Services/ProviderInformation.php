<?php

namespace Smcrow\ContainerInformation\ProviderInformation\Services;

use Illuminate\Foundation\Application;
use ReflectionClass;

/**
 * Service for getting provider information from the Container.
 * @package Services
 */
class ProviderInformation
{

    /**
     * @var Application $application
     */
    private $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * Get the list of registered providers from the Container.
     *
     * @param bool $includeIlluminate Whether or not to include the Illuminate bindings.
     *
     * @return array containing the registered providers.
     */
    public function getProviderList($includeIlluminate = true)
    {
        // Use reflection to get the provider array off of the Application
        // The property we're after is 'serviceProviders' on the Application class.
        $serviceProviderProperty = (new ReflectionClass($this->application))->getProperty('serviceProviders');
        $serviceProviderProperty->setAccessible(true);

        $providers = $serviceProviderProperty->getValue($this->application);

        $foundProviders = [];

        /** @var \Illuminate\Support\ServiceProvider $provider */
        foreach ($providers as $provider) {
            $providerClass = get_class($provider);

            // Only include Illuminate bindings if they were specifically requested.
            if ($includeIlluminate || strpos($providerClass, 'Illuminate\\') === false) {
                $foundProviders[] = [
                    'class'    => $providerClass,
                    'deferred' => $provider->isDeferred() ? 'true' : '',
                    'provides' => implode("\n", $provider->provides()),
                ];
            }
        }

        return $foundProviders;
    }
}
