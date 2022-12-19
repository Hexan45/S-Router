# PHP router library

router library created with PHP language 

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

In `index.php` file you must import router class by enter this code `use Router/Route;`

**Create Route class instance**

```sh
$Route = new Route();
```

**Register path for GET uri method**

```sh
$Route->Get(routePath: '/', routeController: callbackFunction());
or
$Route->Get(routePath: '/', routeController: [somethingControllerClass::class, 'somethingControllerMethod']);
or
$Route->Get(routePath: '/', routeController: 'somethingControllerClass@somethingControllerMethod');
```

**Register path for POST uri method**

```sh
$Route->Post(routePath: '/', routeController: callbackFunction());
or
$Route->Post(routePath: '/', routeController: [somethingControllerClass::class, 'somethingControllerMethod']);
or
$Route->Post(routePath: '/', routeController: 'somethingControllerClass@somethingControllerMethod');
```

**Register dynamic routes**

```sh
$Route->Post(routePath: '/article/$id', routeController: callbackFunction($id));
or
$Route->Post(routePath: '/some/$someID/thing/$thingID', routeController: callbackFunction($someID, $thingID));
```
Important thing! You must add the same names in callbackFunctions/methods parameters as in routePath.

*Example*
`$Route->Post(routePath: '/article/$id', 'AdminController@index');`
So in AdminController class, your index method should look like this
`public function index($id) {...someCode}`

## Methods arguments

**Get**
- `routePath (string)` - Path of uri which will be register in router
- `routeController (callable|array|string)` - Code access details which specify code to run when user enter registered route path

**Post**
- `routePath (string)` - Path of uri which will be register in router
- `routeController (callable|array|string)` - Code access details which specify code to run when user enter registered route path