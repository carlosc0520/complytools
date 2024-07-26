<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>


#### Datatables Fix Deploy
```
composer update
composer dump-autoload

php artisan config:cache
php artisan cache:clear
```

### Read Files saved into Storage
```
php artisan storage:link
```

### Reinit project after change .env
```
php artisan config:cache
```

### Google Captcha Fix Deploy
```
~ composer require anhskohbo/no-captcha
~ Anhskohbo\NoCaptcha\NoCaptchaServiceProvider::class,
~ 'NoCaptcha' => Anhskohbo\NoCaptcha\Facades\NoCaptcha::class,
~ php artisan vendor:publish --provider="Anhskohbo\NoCaptcha\NoCaptchaServiceProvider"
```

### Fix Routing not list
```
~ php artisan route:list
~ php artisan route:cache
```

## Chartisan Fix Deploy
```
~ composer require consoletvs/charts "7.*"
~ php artisan vendor:publish --tag=charts
```

## Run
```
~ php -S localhost:8000 -t public/
```

### NOTES
- [x] webpack.mix.js: Al hacer cambios en este archivo usar yarn dev para que se realicen los cambios, de otra forma si se está usando yarn watch no siempre los detectará.