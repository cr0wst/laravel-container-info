<?php

namespace Smcrow\ContainerInformation;

use Illuminate\Support\ServiceProvider;
use Smcrow\ContainerInformation\BindingInformation\BindingInformationProvider;
use Smcrow\ContainerInformation\ProviderInformation\ProviderInformationProvider;

class ContainerInformationProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->register(BindingInformationProvider::class);
        $this->app->register(ProviderInformationProvider::class);
    }
}
