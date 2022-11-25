<?php

namespace App\Action\User;

use App\Domain\User\Service\UserReader;
use App\Renderer\RedirectRenderer;
use Odan\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

final class UserAction
{
    private SessionInterface $session;

    private Twig $twig;
    private RedirectRenderer $renderer;

    private UserReader $reader;

    public function __construct(
        Twig $twig,
        SessionInterface $session,
        RedirectRenderer $renderer,
        UserReader $reader
    ) {
        $this->twig = $twig;
        $this->session = $session;
        $this->renderer = $renderer;
        $this->reader = $reader;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        if ($this->session->get('user_id') == null) {
            return $this->renderer->redirect($response, '/login');
        }

        $user = $this->reader->getById($this->session->get('user_id'));

        return $this->twig->render(
            $response,
            'views/__user/index.twig',
            [
                'session' => $this->session->get('user_id'),
                'title' => 'hloudBind : ' . $user->username,
                'user' => (array)$user,
            ]
        );
    }
}
