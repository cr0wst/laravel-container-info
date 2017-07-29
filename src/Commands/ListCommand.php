<?php

namespace Smcrow\BindingUtilities\Commands;

use Illuminate\Console\Command;
use Illuminate\Container\Container;
use ReflectionFunction;

class ListCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'binding:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show the bound providers on the IoC container.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param Container $container
     * @return mixed
     */
    public function handle(Container $container)
    {
        // Get the bindings off the injected container.
        $bindings = $container->getBindings();

        $foundBindings = [];

        // Use reflection on each of the binding closures to pull the static $concrete and $abstract variables.
        foreach($bindings as $binding) {
            $reflection = new ReflectionFunction($binding["concrete"]);

            $staticVariables = $reflection->getStaticVariables();

            if (array_has($staticVariables, ['concrete', 'abstract'])) {
                array_push($foundBindings, array_intersect_key($staticVariables, array_flip(['concrete', 'abstract'])));
            }
        }

        $headers = ['Abstract', 'Concrete'];
        $this->table($headers, $foundBindings);
    }
}