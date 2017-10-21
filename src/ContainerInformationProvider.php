<?php

namespace Smcrow\ContainerInformation;

use Illuminate\Support\ServiceProvider;
use Smcrow\ContainerInformation\BindingInformation\BindingInformationProvider;

class ContainerInformationProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(BindingInformationProvider::class);
        $this->app->register(ProviderInformationProvider::class);
    }
}
