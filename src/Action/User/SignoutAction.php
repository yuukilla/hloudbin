<?php

namespace App\Action\User;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Odan\Session\PhpSession;
use App\Renderer\RedirectRenderer;

final class SignoutAction
{
    private RedirectRenderer $renderer;
    private PhpSession $session;

    public function __construct(RedirectRenderer $renderer, PhpSession $session)
    {
        $this->renderer = $renderer;
        $this->session = $session;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $this->session->destroy();
        return $this->renderer->redirect($response, '/');
    }
}