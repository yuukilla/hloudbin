<?php

// Define app routes

use App\Action\API\Account\Auth\AuthAction;
use App\Action\API\Authentification\AuthentificationAction;
use App\Action\API\User\UserAction;
use App\Action\Hloud\HloudAction;
use App\Action\Storage\StorageAction;
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

            // Authentification routes
            $app->get('/login', [FEAccount::class, 'pageSignin'])->setName('login');
            $app->get('/signup', [FEAccount::class, 'pageSignup'])->setName('signup');
            $app->get('/logout', [AuthAction::class, 'signoutCall'])->setName('logout');
        }
    )->add(SessionMiddleware::class);

    // API
    $app->group(
        '/api',
        function (RouteCollectorProxy $app) {
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
                    $app->get('/getByUsername/{username}', [AuthAction::class, 'actionByUsername']);
            //         $app->post('/getByEmail', [UserAction::class, 'actionByEmail']);
                    
            //         $app->post('/update-account', [UserAction::class, 'actionUpdateAccount']);
                }
            );
        }
    )->add(SessionMiddleware::class);

    // $app->get('/upl-test', [StorageAction::class, 'pageBox']);
    // $app->post('/api/upl-test', [\App\Action\API\Storage\StorageAction::class, 'upload']);
};