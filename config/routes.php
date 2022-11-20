<?php

// Define app routes

use App\Action\Home\HomeAction;
use App\Action\About\AboutAction;
use App\Action\Blog\BlogAction;
use App\Action\TermsofService\TermsofServiceAction;
use App\Action\User\SigninAction;
use App\Action\User\SignoutAction;
use App\Action\User\SignupAction;
use App\Action\User\UserAction;
use Odan\Session\Middleware\SessionMiddleware;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    $app->group(
        '',
        function (RouteCollectorProxy $app) {
            $app->get('/', HomeAction::class)->setName('home');
            $app->get('/about', AboutAction::class)->setName('about')->add(SessionMiddleware::class);
            $app->get('/blog', BlogAction::class)->setName('blog');
            $app->get('/terms-of-service', TermsofServiceAction::class)->setName('terms-of-service');
        }
    )->add(SessionMiddleware::class);
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
