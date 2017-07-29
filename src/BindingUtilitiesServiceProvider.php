<?php

namespace Smcrow\BindingUtilities;

use Illuminate\Support\ServiceProvider;
use Smcrow\BindingUtilities\Commands\ListCommand;

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

        $this->commands('command.smcrow.list');
    }

}