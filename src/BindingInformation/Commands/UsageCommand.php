<?php

namespace Smcrow\ContainerInformation\BindingInformation\Commands;

use Illuminate\Console\Command;
use Smcrow\ContainerInformation\BindingInformation\Services\BindingInformation;

class UsageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'binding:usage {--include-illuminate} {--include-vendor} {--exclude=} {--sort}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Searches the source directory for references to the abstracts that are bound';

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
        $this->bindingInformation = $bindingInformation;
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        try {
            $usageList = $this->bindingInformation
                ->getUsageList(
                    $this->option('exclude') ?? '',
                    $this->option('include-illuminate'),
                    $this->option('include-vendor')
                );

            if ($this->option('sort')) {
                ksort($usageList);
            }

            // There might be a better way to do this using array_map but the table method is pretty picky about
            // the array of arrays you feed it.
            $outputArray = [];
            foreach ($usageList as $key => $value) {
                $outputArray[] = ['abstract' => $key, 'locations' => implode("\n", $value)];
            }

            // Build the formatted usage list.
            $headers = ['Abstract', 'Locations'];
            $this->table($headers, $outputArray);
        } catch (\ReflectionException $e) {
            $this->error('Problem retrieving the usage list.');
            $this->error($e->getMessage());
        }
    }
}
