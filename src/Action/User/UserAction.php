<?php

namespace App\Action\User;

use App\Domain\User\Repository\UserRepository;
use App\Renderer\RedirectRenderer;
use Odan\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

/**
 * THIS WAS USED FOR TESTING
 * DONT MIND
 */

final class UserAction
{
    private SessionInterface $session;

    private Twig $twig;
    private RedirectRenderer $renderer;

    private UserRepository $repository;

    public function __construct(Twig $twig, SessionInterface $session, RedirectRenderer $renderer, UserRepository $repository)
    {
        $this->twig = $twig;
        $this->session = $session;
        $this->renderer = $renderer;
        $this->repository = $repository;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        if ($this->session->get('user_id') == null) {
            return $this->renderer->redirect($response, '/login');
        }

        $user = $this->repository->getById($this->session->get('user_id'));

        return $this->twig->render(
            $response,
            'views/user.twig',
            [
                'data' => 'data',
                'user' => $user,
            ]
        );
    }
}