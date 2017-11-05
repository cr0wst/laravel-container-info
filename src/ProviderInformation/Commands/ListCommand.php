<?php

namespace Smcrow\ContainerInformation\ProviderInformation\Commands;

use Illuminate\Console\Command;
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
     * @param ProviderInformation $providerInformation
     */
    public function __construct(ProviderInformation $providerInformation)
    {
        parent::__construct();
        $this->providerInformation = $providerInformation;
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        try {
            $registeredProviders = $this->providerInformation->getProviderList($this->option('include-illuminate'));

            if ($this->option('sort')) {
                asort($registeredProviders);
            }

            $headers = ['Providers', 'Deferred', 'Provides'];
            $this->table($headers, $registeredProviders);
        } catch (\ReflectionException $e) {
            $this->error('Problem retrieving the provider list.');
            $this->error($e->getMessage());
        }
    }
}
