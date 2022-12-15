<?php
    declare(strict_types=1);

    use Router\Route;

    require(__DIR__ . '/vendor/autoload.php');
    require('Controllers/DefaultController.php');

    $Route = new Route();
    $Route->Get('/home', [\Controllers\DefaultController::class, 'home']);