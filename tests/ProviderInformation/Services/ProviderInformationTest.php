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
     * Test the getProviderList method
     */
    public function testGetProviderList()
    {
        // The service providers are an array of objects stored on the Application
        $providers = [
            $providerOne = Mockery::mock('One'),
            $providerTwo = Mockery::mock('Two')
        ];

        $expected = [
            [
                'class' => get_class($providerOne),
                'deferred' => 'true',
                'provides' => '',
            ],
            [
                'class' => get_class($providerTwo),
                'deferred' => '',
                'provides' => "foo\nbar",
            ],
        ];

        $providerOne->shouldReceive('isDeferred')->once()->andReturn(true)
                    ->shouldReceive('provides')->once()->andReturn([]);

        $providerTwo->shouldReceive('isDeferred')->once()->andReturn(false)
                    ->shouldReceive('provides')->once()->andReturn([
                        'foo',
                        'bar',
                    ]);

        // This is nasty but we're going to override the application controller's service providers using reflection
        // since there's no reliable way to build the expected results.
        $application = new Application;
        $property = (new ReflectionClass($application))->getProperty('serviceProviders');
        $property->setAccessible(true);

        $property->setValue($application, $providers);

        $service = new ProviderInformation($application);

        $this->assertEquals($expected, $service->getProviderList());

    }

}
