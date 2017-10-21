<?php

namespace Smcrow\ContainerInformation\BindingInformation;

use Illuminate\Support\ServiceProvider;
use Smcrow\ContainerInformation\BindingInformation\Commands\ListCommand;
use Smcrow\ContainerInformation\BindingInformation\Commands\UsageCommand;

class BindingInformationProvider extends ServiceProvider
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
        $this->app->singleton('command.smcrow.binding.list', function ($app) {
            return $app[ListCommand::class];
        });

        $this->app->singleton('command.smcrow.binding.usage', function ($app) {
            return $app[UsageCommand::class];
        });

        $this->commands('command.smcrow.binding.list', 'command.smcrow.binding.usage');
    }
}
