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
     * @return array containing the registered providers.
     */
    public function getRegisteredProviders()
    {
        // Use reflection to get the provider array off of the Application
        // The property we're after is 'serviceProviders' on the Application class.
        $providers = (new ReflectionClass($this->application))->getProperty('serviceProviders');
        $providers->setAccessible(true);

        return $providers->getValue($this->application);
        
    }
}
