<?php

namespace Smcrow\ContainerInformation\BindingInformation\Commands;

use Illuminate\Console\Command;
use Illuminate\Container\Container;
use Smcrow\ContainerInformation\BindingInformation\Services\BindingInformation;

class UsageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'binding:usage {--include-illuminate} {--sort}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Searches the source directory for references to the abstracts that are bound.';

    /**
     * @var BindingInformation $bindingInformation
     */
    private $bindingInformation;

    /**
     * Create a new command instance.
     *
     * @param BindingInformation $bindingInformation
     */
    public function __construct(BindingInformation $bindingInformation)
    {
        parent::__construct();
        $this->BindingInformation = $bindingInformation;
    }

    /**
     * Execute the console command.
     *
     * @param Container $container
     * @return mixed
     */
    public function handle(Container $container)
    {
        $usageList = $this->BindingInformation->getUsageList($this->option('include-illuminate'));
        if ($this->option('sort')) {
            ksort($usageList);
        }

        // There might be a better way to do this using array_map but the table method is pretty picky about
        // the array of arrays you feed it.
        $outputArray = [];
        foreach($usageList as $key => $value) {
            array_push($outputArray, ['abstract' => $key, 'locations' => implode("\n", $value)]);
        }

        // Build the formatted usage list.
        $headers = ['Abstract', 'Locations'];
        $this->table($headers, $outputArray);

    }
}