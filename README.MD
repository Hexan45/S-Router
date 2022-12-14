# Simple PHP router library

Simple router library created with PHP language 

## Installation

Assuming you don't have initialized `composer.json` file. You must execute code given under below.

```sh
composer init
composer require hexan45/s-router:dev-main
```

In main project folder create .htaccess file and paste this code

```sh
RewriteEngine On
RewriteBase /
RewriteCond %{REQUEST_FILENAME} !-f 
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^ index.php?request=%{THE_REQUEST} [L]
```

To start using library create file named `index.php` in your root directory and use build in `include()` or `require()` function to include autoloading PSR-4 file named `autoload.php`.

## Usage

In `index.php` file you must import router class by enter this code `use Router/router;`

**Register path for GET uri method**

```sh
router::get(routePath: '/', routeController: callbackFunction());
or
router::get(routePath: '/', routeController: [somethingControllerClass::class, 'somethingControllerMethod']);
or
router::get(routePath: '/', routeController: 'somethingControllerClass@somethingControllerMethod');
```
**Resolving all registered methods**

To resolve the registered routes to actually url you must use `router::resolve()` under below all methods which register routes like that `router::get()`

## Contructions of the methods in router.php

**Get**
`static public function get(string $routePath, callable|array|string $routeController) : void`

**Resolve**
`static public function resolve() : void`