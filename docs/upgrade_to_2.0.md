# Upgrade Guide v2.0

This guide covers the changes needed to upgrade the Payum Laravel Package from 1.x to 2.0.

## Breaking Changes

### 1. **PHP Version Requirement**

Laravel 12 and PHP 8.2 or higher are required. Update your server to PHP 8.2+ before upgrading.


### 2. **Service Provider Registration**

The package now uses modern Laravel service provider patterns. No changes needed if you're using the default registration:

```php
// config/app.php
'providers' => [
    Payum\LaravelPackage\PayumServiceProvider::class,
],
```

### 3. **Configuration Changes**

If you're using the package with Laravel 12's new bootstrap structure, register the service provider in `config/app.php` or use the new `bootstrap/providers.php` file:

```php
// bootstrap/providers.php
return [
    Payum\LaravelPackage\PayumServiceProvider::class,
];
```

### 4. **Route Registration**

Routes are now registered automatically by the service provider. If you've customized routes, they should continue to work without changes.

### 5. **Controller Updates**

The package now uses modern Laravel helper functions instead of facades:

- `\App::make()` → `app()`
- `\Request::` → `request()`
- `\Response::make()` → `response()`
- `\Redirect::to()` → `redirect()`
- `\URL::route()` → `route()`

### 6. **Database Schema**

No changes to existing database schemas are required. The Eloquent models remain compatible.

## Migration Steps

1. **Update PHP to 8.2+**
2. **Update composer dependencies**:
   ```bash
   composer require payum/payum-laravel-package:"^2.0"
   ```
3. **Clear Laravel caches**:
   ```bash
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   ```
4. **Test your payment flows**

## Troubleshooting

### Common Issues

1. **Service Provider Not Found**
   - Ensure you've cleared the config cache: `php artisan config:clear`
   - Check that the service provider is registered in `config/app.php`

2. **Route Not Found**
   - Clear route cache: `php artisan route:clear`
   - Check that routes are being registered by running: `php artisan route:list`

3. **Class Not Found Errors**
   - Run `composer dump-autoload` to regenerate autoload files
   - Ensure all dependencies are up to date

### Getting Help

- Check the [documentation](docs/index.md)
- Search existing [issues](https://github.com/Payum/PayumLaravelPackage/issues)
- Create a new issue with detailed information about your setup

