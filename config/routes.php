<?php

// Define app routes

use App\Action\API\Account\Auth\AuthAction;
use App\Action\Hloud\Account\AccountAction;
use App\Action\Hloud\HloudAction;
use Odan\Session\Middleware\SessionMiddleware;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use App\Action\Hloud\Account\AccountAction as FEAccount;

return function (App $app) {
    // End-user accessable endpoints
    $app->group(
        '',
        function (RouteCollectorProxy $app) {
            // General routes
            $app->get('/', [HloudAction::class, 'pageLanding'])->setName('landing');

            // User routes
            $app->get('/account', [FEAccount::class, 'pageAccount'])->setName('account');

            // front-end Authentification routes
            $app->get('/login', [FEAccount::class, 'pageSignin'])->setName('login');
            $app->get('/signup', [FEAccount::class, 'pageSignup'])->setName('signup');
            $app->get('/logout', [AuthAction::class, 'signoutCall'])->setName('logout');
        }
    )->add(SessionMiddleware::class);

    // API
    $app->group(
        '/api',
        function (RouteCollectorProxy $app) {
            // Back-end Authentification routes
            $app->group(
                '/auth',
                function (RouteCollectorProxy $app) {
                        $app->post('/login', [AuthAction::class, 'signinCall']);
                        $app->post('/signup', [AuthAction::class, 'signupCall']);
                        $app->get('/logout', [AuthAction::class, 'signoutCall']);
                    }
            );
            $app->group(
                '/user',
                function (RouteCollectorProxy $app) {
                    $app->get('/exists/username', [AuthAction::class, 'existsUsername']);
                    $app->get('/exists/email', [AuthAction::class, 'existsEmail']);

                    $app->post('/update', [AccountAction::class, 'updateAccount']); // not tested yet
                    
            //         $app->post('/update-account', [UserAction::class, 'actionUpdateAccount']);
                }
            );
        }
    )->add(SessionMiddleware::class);

    // $app->get('/upl-test', [StorageAction::class, 'pageBox']);
    // $app->post('/api/upl-test', [\App\Action\API\Storage\StorageAction::class, 'upload']);
};