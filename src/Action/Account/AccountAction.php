<?php

namespace App\Action\Account;

use App\Domain\User\Service\UserReader;
use App\Renderer\RedirectRenderer;
use Odan\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

final class AccountAction
{
    private SessionInterface $session;
    private UserReader $userReader;
    private RedirectRenderer $renderer;
    private Twig $twig;

    public function __construct(
        SessionInterface $session,
        UserReader $userReader,
        RedirectRenderer $renderer,
        Twig $twig
    ) {
        $this->session = $session;
        $this->userReader = $userReader;
        $this->renderer = $renderer;
        $this->twig = $twig;
    }

    public function pageLogin(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        if ($this->bolSessionActive()) {
            return $this->renderer->redirect($response, '/account');
        }

        return $this->twig->render(
            $response,
            'hloud/auth/login.twig',
            [
                'title' => 'hloudBin Login',
                'hideModalLink' => true,
            ],
        );
    }

    public function pageSignup(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        if ($this->bolSessionActive()) {
            return $this->renderer->redirect($response, '/account');
        }

        return $this->twig->render(
            $response,
            'hloud/auth/signup.twig',
            [
                'title' => 'hloudBin Signup',
                'hideModalLink' => true
            ],
        );
    }

    public function pageAccount(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        if ( !$this->bolSessionActive() ) {
            return $this->renderer->redirect($response, '/login');
        }

        $objUser = $this->userReader->getUserById($this->session->get('hloudbin_userID'));

        return $this->twig->render(
            $response,
            'hloud/account/account.twig',
            [
                'title' => 'hloudBin Account',
                'session' => $this->session->get('hloudbin_userID'),
                'user' => (array)$objUser
            ]
        );
    }

    public function bolSessionActive(): bool
    {
        return !empty($this->session->get('hloudbin_userID'));
    }
}