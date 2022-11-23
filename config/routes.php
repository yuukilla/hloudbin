<?php

// Define app routes

use App\Action\About\AboutAction;
use App\Action\API\User\Auth\SigninAction;
use App\Action\API\User\Auth\SignoutAction;
use App\Action\API\User\Auth\SignupAction;
use App\Action\Blog\BlogAction;
use App\Action\Home\HomeAction;
use App\Action\TermsofService\TermsofServiceAction;
use App\Action\Upload\UploadAction;
use App\Action\User\UserAction;
use Odan\Session\Middleware\SessionMiddleware;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    $app->group(
        '',
        function (RouteCollectorProxy $app) {
            $app->get('/', HomeAction::class)->setName('home');
            $app->get('/about', AboutAction::class)->setName('about');
            $app->get('/blog', BlogAction::class)->setName('blog');
            $app->get('/terms-of-service', TermsofServiceAction::class)->setName('terms-of-service');
            $app->get('/user', UserAction::class)->setName('user');
            $app->map(['GET', 'POST'], '/upload', UploadAction::class)->setName('upload');

            // frontend auth routes
            $app->get('/signin', \App\Action\User\SigninAction::class)->setName('signin');
            $app->get('/signup', \App\Action\User\SignupAction::class)->setName('signup');
            $app->get('/signout', SignoutAction::class)->setName('signout');
        }
    )->add(SessionMiddleware::class);

    // API
    $app->group(
        '/api',
        function (RouteCollectorProxy $app) {
            $app->group(
                '/auth',
                function (RouteCollectorProxy $app) {
                    $app->post('/signup', SignupAction::class);
                    $app->post('/signin', SigninAction::class);
                    $app->get('/signout', SignoutAction::class);
                }
            );
            $app->group(
                '/user',
                function (RouteCollectorProxy $app) {
                    $app->post('/change-password', ChangePasswordAction::class);
                    $app->post('/update-account', UpdateAccountAction::class);
                    $app->post('/delete-account', DeleteAccountAction::class);
                }
            );
        }
    )->add(SessionMiddleware::class);
};
