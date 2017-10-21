<?php

namespace Smcrow\ContainerInformation\ProviderInformation;

use Illuminate\Support\ServiceProvider;
use Smcrow\ContainerInformation\ProviderInformation\Commands\ListCommand;

class ProviderInformationProvider extends ServiceProvider
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
        $this->app->singleton('command.smcrow.provider.list', function($app) {
           return $app[ListCommand::class];
        });

        $this->commands('command.smcrow.provider.list');
    }

}