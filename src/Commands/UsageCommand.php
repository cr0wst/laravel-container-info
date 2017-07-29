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
    protected $signature = 'binding:usage';

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
     * @return void
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
        $this->line($this->bindingService->getUsageList());
    }
}