<?php
    declare(strict_types=1);

    use Router\router;

    require(__DIR__ . '/vendor/autoload.php');
    require('Controllers/DefaultController.php');

    router::get('/', function() {
        echo 'test';
    });

    router::get('/home', [\Controllers\DefaultController::class, 'home']);

    router::get('/contact', '\Controllers\DefaultController@contact');

    router::resolve();

    echo '<pre>';
    print_r(router::showRoutes());