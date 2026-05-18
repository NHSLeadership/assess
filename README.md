<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://upload.wikimedia.org/wikipedia/commons/d/d3/National_Health_Service_%28England%29_logo.svg" width="200"></a>
<br><span style="color:#fff;font-size:20px">Leadership Academy</span></p>

# NHS Leadership Academy - Assessment Tool

A web application for self-assessment and 360-degree feedback, built with [Laravel](https://laravel.com/) and [Filament](https://filamentphp.com/).

**License:** [MIT](LICENCE.md) | **Security:** [Security Policy](SECURITY.md) | **Contributing:** [Contribute](CONTRIBUTING.md) | **Code of Conduct:** [Read](CODE_OF_CONDUCT.md)

## Community

We welcome contributions from the community. Please read our [Code of Conduct](CODE_OF_CONDUCT.md) and [Contributing Guidelines](CONTRIBUTING.md) before participating.

For security concerns, see our [Security Policy](SECURITY.md).

### Installation
> Run `composer install`. If applicable, compile Node assets. Please ensure your PHP version meets the Composer requirement (PHP 8+).

When you first install it, the .env will not be present

```cp .env.example .env```
```php artisan key:generate```

And tweak your default settings if needed (mysql, security, etc.)

### Locally run the app with (recommended)
> php artisan serve

This will serve the application at:
> http://localhost/

Optionally you can specify a port with:
> php artisan serve --port=8005

This will serve the application at:
> http://localhost:8005/

### Compiling assets

> npm run build

### Optimising Filament components

When developing new filament components in dashboard you may need to optimise the backend to load them quicker.

The following will create cache files in the `bootstrap/cache/filament` directory:

```php artisan filament:cache-components```

The following will cache the configuration files and routes and will optimise filament components (including stage and production):

```php artisan optimize```

Additionally, you may consider the following command to cache frontend assets:

```php artisan icon:cache```

### Creating symbolic link to storage public folder
To make files in `storage/app/public` (such as certificate background images) accessible from the web:

```php artisan storage:link```

This will connect [/var/www/html/public/storage] to [/var/www/html/storage/app/public].

#### Running tests

```php artisan test```

### Umami configuration

Umami runtime configuration is DB-backed via Spatie Settings.
config/umami.php is used only for defaults and bootstrap.

## License

This software is licensed under the [MIT license](LICENCE.md).