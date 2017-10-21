<?php

namespace Smcrow\ContainerInformation\ProviderInformation\Commands;

use Illuminate\Console\Command;
use Illuminate\Container\Container;
use Smcrow\ContainerInformation\ProviderInformation\Services\ProviderInformation;

class ListCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'provider:list {--include-illuminate} {--sort}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show the registered service providers on the IoC container';

    /**
     * @var ProviderInformation $providerInformation
     */
    private $providerInformation;

    /**
     * Create a new command instance.
     *
     */
    public function __construct(ProviderInformation $providerInformation)
    {
        parent::__construct();
        $this->providerInformation = $providerInformation;
    }

    /**
     * Execute the console command.
     *
     * @param Container $container
     * @return mixed
     */
    public function handle(Container $container)
    {
        $registeredProviders = $this->providerInformation->getProviderList($this->option('include-illuminate'));

        if ($this->option('sort')) {
            asort($registeredProviders);
        }

        $headers = ['Providers', 'Deferred', 'Provides'];
        $this->table($headers, $registeredProviders);
    }
}
