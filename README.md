# Artisan Binding-Utilities
This is a suite of custom commands for Artisan that assists working with IoC bindings.  The following commands are added:
* `binding:list` - Lists the registered bindings by showing the abstract (interface) and concrete class that will be injected.

# Usage
## Install Through Composer
```
composer require smcrow/binding-utilities
```

## Register the Command
You'll need to register the command in order for it to be usable.  This will add the provider for the local environment:
```php
public function register()
{
    if ($this->app->environment() == 'local') {
        $this->app->register(BindingUtilitiesServiceProvider::class);
    }
}
```