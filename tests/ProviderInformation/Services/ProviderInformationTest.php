<?php

namespace Smcrow\ContainerInformation\ProviderInformation\Tests;

use Illuminate\Foundation\Application;
use Mockery;
use PHPUnit\Framework\TestCase;
use Smcrow\ContainerInformation\ProviderInformation\Services\ProviderInformation;
use ReflectionClass;

class ProviderInformationTest extends TestCase
{

    private $applicationMock;

    public function setUp()
    {
        parent::setUp();
    }

    /**
     * Test the getRegisteredProviders method
     */
    public function testGetRegisteredProviders()
    {

        // The service providers are an array of objects stored on the Application
        $providers = [
            Mockery::mock('One'),
            Mockery::mock('Two')
        ];

        // This is nasty but we're going to override the application controller's service providers using reflection
        // since there's no reliable way to build the expected results.
        $application = new Application;
        $property = (new ReflectionClass($application))->getProperty('serviceProviders');
        $property->setAccessible(true);

        $property->setValue($application, $providers);

        $service = new ProviderInformation($application);

        $this->assertEquals($service->getRegisteredProviders(), $providers);

    }

}
