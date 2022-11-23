<?php

namespace App\Action\API\User\Auth;

use App\Domain\User\Service\UserCreator;
use App\Renderer\RedirectRenderer;
use Odan\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class SignupAction
{
    private UserCreator $creator;

    private SessionInterface $session;

    private RedirectRenderer $renderer;

    public function __construct(UserCreator $creator, SessionInterface $session, RedirectRenderer $renderer)
    {
        $this->creator = $creator;
        $this->session = $session;
        $this->renderer = $renderer;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $arrData = (array)$request->getParsedBody();

        // Unhandled exceptions !FIX

        $intUserId = $this->creator->create($arrData);

        $this->session->set('user_id', $intUserId);
        $this->session->save();

        return $this->renderer->redirect($response, '/');
    }
}
