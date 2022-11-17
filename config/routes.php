<?php

// Define app routes

use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    // Redirect to Swagger documentation
    $app->get('/', \App\Action\Home\HomeAction::class)->setName('home');

    // API
    $app->group(
        '/api',
        function (RouteCollectorProxy $app) {
            $app->post('/signup', \App\Action\User\SignupAction::class);
            $app->post('/signin', \App\Action\User\SigninAction::class);
            $app->get('/signout', \App\Action\User\SignoutAction::class);
            $app->get('/user', \App\Action\User\UserAction::class);
        }
    );
};
