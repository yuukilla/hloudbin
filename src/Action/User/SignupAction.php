<?php

namespace App\Action\User;

use App\Renderer\RedirectRenderer;
use Odan\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

final class SignupAction
{
    private SessionInterface $session;

    private RedirectRenderer $renderer;

    private Twig $twig;

    public function __construct(SessionInterface $session, RedirectRenderer $renderer, Twig $twig)
    {
        $this->session = $session;
        $this->renderer = $renderer;
        $this->twig = $twig;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        if ($this->session->get('user_id') != null) {
            return $this->renderer->redirect($response, '/user');
        }

        return $this->twig
            ->render(
                $response,
                'views/__auth/signup.twig'
            );
    }
}