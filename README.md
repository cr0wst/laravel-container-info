# Artisan Binding-Utilities

[![Latest Stable Version](https://poser.pugx.org/smcrow/binding-utilities/v/stable)](https://packagist.org/packages/smcrow/binding-utilities) [![Latest Unstable Version](https://poser.pugx.org/smcrow/binding-utilities/v/unstable)](https://packagist.org/packages/smcrow/binding-utilities) [![Total Downloads](https://poser.pugx.org/smcrow/binding-utilities/downloads)](https://packagist.org/packages/smcrow/binding-utilities) 

This is a suite of custom commands for Artisan that assists working with IoC bindings.  The following commands are added:
* `binding:list` - Lists the registered bindings by showing the abstract (interface) and concrete class that will be injected.
    * `--include-illuminate` - Indicates that Illuminate classes should be included.  They are not included by default.
* `binding:usage` - Lists the registered bindings and which files they are referenced in.
    * `--include-illuminate` - Indicates that Illuminate classes should be included.  They are not included by default.
    * `--sort` - Indicates that the information should be sorted.

# Usage
## Install Through Composer
```
composer require smcrow/binding-utilities
```

## Register the Command
You'll need to register the command in order for it to be usable.  Modify the `register` method of `AppServiceProvider`  This will add the provider for the local environment:
```php
public function register()
{
    if ($this->app->environment() === 'local') {
        $this->app->register(BindingUtilitiesServiceProvider::class);
    }
}
```

## Example Usage
```
php artisan binding:list
```
Here's sample output from the `binding:list` command from my `LeaseTracker` application.
```
+-----------------------------------------------------------------+--------------------------------------------------------------+
| Abstract                                                        | Concrete                                                     |
+-----------------------------------------------------------------+--------------------------------------------------------------+
| Illuminate\Contracts\Http\Kernel                                | LeaseTracker\Http\Kernel                                     |
| Illuminate\Contracts\Console\Kernel                             | LeaseTracker\Console\Kernel                                  |
| Illuminate\Contracts\Debug\ExceptionHandler                     | LeaseTracker\Exceptions\Handler                              |
| Illuminate\Session\Middleware\StartSession                      | Illuminate\Session\Middleware\StartSession                   |
| LeaseTracker\Services\Vehicle\VehicleServiceInterface           | LeaseTracker\Services\Vehicle\VehicleService                 |
| LeaseTracker\Services\Mileage\MileageServiceInterface           | LeaseTracker\Services\Mileage\MileageService                 |
| LeaseTracker\Services\Calculation\CalculationServiceInterface   | LeaseTracker\Services\Calculation\CalculationService         |
| LeaseTracker\Services\VehicleImage\VehicleImageServiceInterface | LeaseTracker\Services\VehicleImage\GoogleVehicleImageService |
| LeaseTracker\Repositories\VehicleRepositoryInterface            | LeaseTracker\Repositories\VehicleRepository                  |
| LeaseTracker\Repositories\MileEntryRepositoryInterface          | LeaseTracker\Repositories\MileEntryRepository                |
| Illuminate\Console\Scheduling\ScheduleFinishCommand             | Illuminate\Console\Scheduling\ScheduleFinishCommand          |
| Illuminate\Console\Scheduling\ScheduleRunCommand                | Illuminate\Console\Scheduling\ScheduleRunCommand             |
| Illuminate\Contracts\Pipeline\Hub                               | Illuminate\Pipeline\Hub                                      |
+-----------------------------------------------------------------+--------------------------------------------------------------+
```

# Feedback and Contributions
Please feel free to offer suggestions by submitting an Issue.  Alternatively, submit a pull request with any features you wish to add.  This is a work-in-progress, and I would welcome any and all feedback.
