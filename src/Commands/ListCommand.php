<?php

namespace Smcrow\BindingUtilities\Commands;

use Illuminate\Console\Command;
use Illuminate\Container\Container;
use ReflectionFunction;
use Smcrow\BindingUtilities\Services\BindingService;

class ListCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'binding:list {--include-illuminate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show the bound providers on the IoC container.';

    /**
     * @var BindingService $bindingService
     */
    private $bindingService;

    /**
     * Create a new command instance.
     *
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
        $foundBindings = $this->bindingService->getBindingList($this->option('include-illuminate'));

        $headers = ['Abstract', 'Concrete'];
        $this->table($headers, $foundBindings);
    }
}