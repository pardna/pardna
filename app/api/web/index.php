<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpKernel\Debug\ErrorHandler;
use Symfony\Component\HttpKernel\Debug\ExceptionHandler;

// set the error handling
ini_set('display_errors', 1);
error_reporting(-1);
ErrorHandler::register();

$app = new Silex\Application();


$app['debug'] = true;

$app->get('/hello/{name}', function ($name) use ($app) {
    return 'Hello '.$app->escape($name);
});

/**
* list pardnas
*/
$app->get('/pardna', function () use ($app) {
    return 'hello';
});

/**
* list pardna with details
* - users
* - payments
*/
$app->get('/pardna/{id}', function () use ($app) {
    return 'hello';
});

$app->get('/pardna/{id}/users', function ($id) use ($app) {
    return 'hello';
});

$app->get('/pardna/{pardna_id}/user/{user_id}/details', function ($pardna_id, $user_id) use ($app) {
    return 'hello';
});

$app->get('/pardna/{pardna_id}/user/{user_id}/payments', function ($pardna_id, $user_id) use ($app) {
    return 'hello';
});

$app->run();
