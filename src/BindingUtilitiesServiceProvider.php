<?php

namespace Smcrow\BindingUtilities;

use Illuminate\Support\ServiceProvider;
use Smcrow\BindingUtilities\Commands\ListCommand;
use Smcrow\BindingUtilities\Commands\UsageCommand;

class BindingUtilitiesServiceProvider extends ServiceProvider
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
        $this->app->singleton('command.smcrow.list', function($app) {
           return $app[ListCommand::class];
        });

        $this->app->singleton('command.smcrow.usage', function($app) {
            return $app[UsageCommand::class];
        });

        $this->commands('command.smcrow.list', 'command.smcrow.usage');
    }

}