<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://upload.wikimedia.org/wikipedia/commons/d/d3/National_Health_Service_%28England%29_logo.svg" width="200"></a>
<br><span style="color:#fff;font-size:20px">Leadership Academy</span></p>

## Self Assessment 360 Tools
A web application built with the Laravel framework. 

### Installation 
> Just run composer install and compile node content when applicable, please check the php version requirement as per composer (php 8+)
```composer install```

When you first install it, the .env will not be present

```cp .env.example .env```
```php artisan key:generate```

And tweak your default settings if needed (mysql, security etc...)

### Locally run the app with (recommended) 
> php artisan serve

This will serve the application at:
> http://localhost/

Optionally you can specify a port with:
> php artisan serve --port=8005

This will serve the application at:
> http://localhost:8005/

### Compiling assets

Run development build
```npm run dev```

Run development watch
```npm run watch```
```watch-poll```

Run development watch
```npm run production```

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

sail php ./vendor/bin/pest

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
