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
    if ($this->app->environment() === 'local') {
        $this->app->register(BindingUtilitiesServiceProvider::class);
    }
}
```

# Feedback and Contributions
Please feel free to offer suggestions by submitting an Issue.  Alternatively, submit a pull request with any features you wish to add.  This is a work-in-progress, and I would welcome any and all feedback.