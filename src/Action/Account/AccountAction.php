<?php

namespace App\Action\Account;
use App\Renderer\RedirectRenderer;
use Odan\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

final class AccountAction
{
    private SessionInterface $session;
    private RedirectRenderer $renderer;
    private Twig $twig;

    public function __construct(
        SessionInterface $session,
        RedirectRenderer $renderer,
        Twig $twig
    ) {
        $this->session = $session;
        $this->renderer = $renderer;
        $this->twig = $twig;
    }

    public function pageLogin(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        if (!$this->bolSessionActive()) {
            return $this->renderer->redirect($response, '/account');
        }

        return $this->twig->render(
            $response,
            'hloud/auth/login.twig',
            ['title' => 'hloudBin Login'],
        );
    }

    public function pageSignup(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        if (!$this->bolSessionActive()) {
            return $this->renderer->redirect($response, '/account');
        }

        return $this->twig->render(
            $response,
            'hloud/auth/signup.twig',
            ['title' => 'hloudBin Signup'],
        );
    }

    public function bolSessionActive(): bool
    {
        return (bool)$this->session->get('hloudBin_userID') == null;
    }
}