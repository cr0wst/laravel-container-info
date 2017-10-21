<?php

namespace Smcrow\BindingUtilities\Tests;

use Illuminate\Container\Container;
use Mockery;
use PHPUnit\Framework\TestCase;
use Smcrow\ContainerInformation\BindingInformation\Services\BindingInformation;

class BindingInformationTest extends TestCase
{

    private $containerMock;

    public function setUp()
    {
        parent::setUp();
        $this->containerMock = Mockery::mock(Container::class);
    }

    /**
     * Test the getBindingListMethod with both Illuminate and non-Illuminate values.
     */
    public function testGetBindingList()
    {

        // The bindings array is an array of arrays with concrete's mapped to Closures.  This is my best attempt
        // at emulating how the container stores bindings.

        // If anybody has a decent way of building this array please PR.
        $bindings = [
            ["concrete" =>
            function () {
                static $concrete = "Smcrow\TestContracts\Contract1";
                static $abstract = "Smcrow\TestImplementations\Implementation1";
                }
            ],
            ["concrete" =>
                function () {
                    static $concrete = "Smcrow\TestContracts\Contract2";
                    static $abstract = "Smcrow\TestImplementations\Implementation2";
                }
            ],
            ["concrete" =>
                function () {
                    static $concrete = "Smcrow\TestContracts\Contract3";
                    static $abstract = "Smcrow\TestImplementations\Implementation3";
                }
            ],
            ["concrete" =>
                function () {
                    static $concrete = "Illuminate\TestContracts\Contract1";
                    static $abstract = "Illuminate\TestImplementations\Implementation1";
                }
            ],
            ["concrete" =>
                function () {
                    static $concrete = "Illuminate\TestContracts\Contract2";
                    static $abstract = "Illuminate\TestImplementations\Implementation2";
                }
            ],
            ["concrete" =>
                function () {
                    static $concrete = "Illuminate\TestContracts\Contract3";
                    static $abstract = "Illuminate\TestImplementations\Implementation3";
                }
            ]
        ];

        $this->containerMock->shouldReceive('getBindings')->once()->andReturn($bindings);

        $service = new BindingInformation($this->containerMock);

        $expectedWithIlluminate =
            [
                [
                    "concrete" => "Smcrow\TestContracts\Contract1",
                    "abstract" => "Smcrow\TestImplementations\Implementation1",
                ],
                [
                    "concrete" => "Smcrow\TestContracts\Contract2",
                    "abstract" => "Smcrow\TestImplementations\Implementation2",
                ],
                [
                    "concrete" => "Smcrow\TestContracts\Contract3",
                    "abstract" => "Smcrow\TestImplementations\Implementation3",
                ],
                [
                    "concrete" => "Illuminate\TestContracts\Contract1",
                    "abstract" => "Illuminate\TestImplementations\Implementation1",
                ],
                [
                    "concrete" => "Illuminate\TestContracts\Contract2",
                    "abstract" => "Illuminate\TestImplementations\Implementation2",
                ],
                [
                    "concrete" => "Illuminate\TestContracts\Contract3",
                    "abstract" => "Illuminate\TestImplementations\Implementation3",
                ]
            ];

        $expectedWithoutIlluminate =
            [
                [
                    "concrete" => "Smcrow\TestContracts\Contract1",
                    "abstract" => "Smcrow\TestImplementations\Implementation1",
                ],
                [
                    "concrete" => "Smcrow\TestContracts\Contract2",
                    "abstract" => "Smcrow\TestImplementations\Implementation2",
                ],
                [
                    "concrete" => "Smcrow\TestContracts\Contract3",
                    "abstract" => "Smcrow\TestImplementations\Implementation3",
                ]
            ];

        $resultsWithIlluminate = $service->getBindingList();
        $resultsWithoutIlluminate = $service->getBindingList(false);

        $this->assertEquals($resultsWithIlluminate, $expectedWithIlluminate);
        $this->assertEquals($resultsWithoutIlluminate, $expectedWithoutIlluminate);

    }

}
