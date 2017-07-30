<?php

namespace Smcrow\BindingUtilities\Commands;

use Illuminate\Console\Command;
use Illuminate\Container\Container;
use ReflectionFunction;
use Smcrow\BindingUtilities\Services\BindingService;

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
     * @var BindingService $bindingService
     */
    private $bindingService;

    /**
     * Create a new command instance.
     *
     * @param BindingService $bindingService
     */
    public function __construct(BindingService $bindingService)
    {
        parent::__construct();
        $this->bindingService = $bindingService;
    }

    /**
     * Execute the console command.
     *
     * @param Container $container
     * @return mixed
     */
    public function handle(Container $container)
    {
        $usageList = $this->bindingService->getUsageList($this->option('include-illuminate'));
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