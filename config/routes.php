<?php

// Define app routes

use App\Action\Account\AccountAction;
use App\Action\API\Authentification\AuthentificationAction;
use App\Action\Hloud\HloudAction;
use App\Action\Upload\UploadAction;
use Odan\Session\Middleware\SessionMiddleware;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {
    // End-user accessable endpoints
    $app->group(
        '',
        function (RouteCollectorProxy $app) {
            // General routes
            $app->get('/', [HloudAction::class, 'pageLanding'])->setName('landing');
            $app->get('/about', [HloudAction::class, 'pageAbout'])->setName('about');
            $app->get('/blog', [HloudAction::class, 'pageBlog'])->setName('blog');
            $app->get('/contact', [HloudAction::class, 'pageContact'])->setName('contact');
            $app->get('/terms-of-service', [HloudAction::class, 'pageTofS'])->setName('terms-of-service');

            // Service routes
            $app->map(
                ['GET', 'POST'],
                '/upload',
                UploadAction::class
            )->setName('upload');

            // User routes
            $app->get('/account', [AccountAction::class, 'pageAccount'])->setName('account');

            // Authentification routes
            $app->get('/login', [AccountAction::class, 'pageLogin'])->setName('login');
            $app->get('/signup', [AccountAction::class, 'pageSignup'])->setName('signup');
            $app->get('/logout', [AuthentificationAction::class, 'actionLogout'])->setName('logout');
        }
    )->add(SessionMiddleware::class);

    // API
    $app->group(
        '/api',
        function (RouteCollectorProxy $app) {
            $app->group(
                '/auth',
                function (RouteCollectorProxy $app) {
                        $app->post('/login', [AuthentificationAction::class, 'actionLogin']);
                        $app->post('/signup', [AuthentificationAction::class, 'actionSignup']);
                        $app->get('/logout', [AuthentificationAction::class, 'actionLogout']);
                    }
            );
        }
    )->add(SessionMiddleware::class);
};