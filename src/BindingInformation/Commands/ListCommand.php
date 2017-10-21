<?php

namespace Smcrow\ContainerInformation\BindingInformation\Commands;

use Illuminate\Console\Command;
use Illuminate\Container\Container;
use Smcrow\ContainerInformation\BindingInformation\Services\BindingInformation;

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
    protected $description = 'Show the bound providers on the IoC container';

    /**
     * @var BindingInformation $bindingInformation
     */
    private $bindingInformation;

    /**
     * Create a new command instance.
     *
     */
    public function __construct(BindingInformation $bindingInformation)
    {
        parent::__construct();
        $this->bindingInformation = $bindingInformation;
    }

    /**
     * Execute the console command.
     *
     * @param Container $container
     * @return mixed
     */
    public function handle(Container $container)
    {
        $foundBindings = $this->bindingInformation->getBindingList($this->option('include-illuminate'));

        $headers = ['Abstract', 'Concrete'];
        $this->table($headers, $foundBindings);
    }
}
