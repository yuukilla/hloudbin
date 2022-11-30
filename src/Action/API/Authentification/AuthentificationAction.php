<?php

namespace App\Action\API\Authentification;

use App\Domain\User\Service\UserCreator;
use App\Domain\User\Service\UserReader;
use App\Renderer\RedirectRenderer;
use Odan\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class AuthentificationAction
{
    private UserCreator $userCreator;
    private UserReader $userReader;
    private SessionInterface $session;
    private RedirectRenderer $renderer;

    public function __construct(
        UserCreator $userCreator,
        UserReader $userReader,
        SessionInterface $session,
        RedirectRenderer $redirectRenderer
    ) {
        $this->userCreator = $userCreator;
        $this->userReader = $userReader;
        $this->session = $session;
        $this->renderer = $redirectRenderer;
    }

    public function actionSignup(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = (array)$request->getParsedBody();
        

        // Still unhandled exceptions lmao

        $userId = $this->userCreator->createUser($data);

        $this->session->set('hloudbin_userID', $userId);
        $this->session->save();

        return $this->renderer->redirect($response, '/');
    }

    public function actionLogin(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = (array)$request->getParsedBody();

        try {
            $user = $this->userReader->getUserByName($data['username']);

            $bolPasswordVerified = password_verify($data['password'], $user->password);

            if (!$bolPasswordVerified) {
                $flash = $this->session->getFlash();
                $flash->add('error', 'Incorrect username and/or password');

                return $this->renderer->redirect($response, '/login');
            }

            $this->session->set('hloudbin_userID', $user->id);
            $this->session->save();

            return $this->renderer->redirect($response, '/');
        } catch (\Exception $exc) {
            $flash = $this->session->getFlash();
            $flash->add('error', $exc->getMessage());

            return $this->renderer->redirect($response, '/login');
        }
    }

    public function actionLogout(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $this->session->destroy();

        return $this->renderer->redirect($response, '/');
    }
}