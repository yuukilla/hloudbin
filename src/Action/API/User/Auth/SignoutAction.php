<?php

namespace App\Action\API\User\Auth;

use App\Renderer\RedirectRenderer;
use Odan\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class SignoutAction
{
    private SessionInterface $session;

    private RedirectRenderer $renderer;

    public function __construct(SessionInterface $session, RedirectRenderer $renderer)
    {
        $this->session = $session;
        $this->renderer = $renderer;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $this->session->destroy();
        return $this->renderer
            ->redirect($response, '/');
    }
}