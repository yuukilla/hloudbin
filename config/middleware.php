<?php

use App\Middleware\ValidationExceptionMiddleware;
use Odan\Session\Middleware\SessionMiddleware;
use Selective\BasePath\BasePathMiddleware;
use Slim\App;
use Slim\Middleware\ErrorMiddleware;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

return function (App $app) {
    $app->addBodyParsingMiddleware();
    $app->add(ValidationExceptionMiddleware::class);
    $app->addRoutingMiddleware();
    $app->add(BasePathMiddleware::class);
    $app->add(ErrorMiddleware::class);
    $app->add(TwigMiddleware::createFromContainer($app, Twig::class));
    $app->add(SessionMiddleware::class);
};
