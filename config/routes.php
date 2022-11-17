<?php

// Define app routes

use App\Action\Home\HomeAction;
use App\Action\TermsofService\TermsofServiceAction;
use App\Action\User\SigninAction;
use App\Action\User\SignoutAction;
use App\Action\User\SignupAction;
use App\Action\User\UserAction;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    // Redirect to Swagger documentation
    $app->get('/', HomeAction::class)->setName('home');
    $app->get('/terms-of-service', TermsofServiceAction::class)->setName('terms-of-service');

    // API
    $app->group(
        '/api',
        function (RouteCollectorProxy $app) {
            $app->post('/signup', SignupAction::class);
            $app->post('/signin', SigninAction::class);
            $app->get('/signout', SignoutAction::class);
            $app->get('/user', UserAction::class);
        }
    );
};
