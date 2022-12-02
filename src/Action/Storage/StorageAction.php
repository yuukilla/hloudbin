<?php

namespace App\Action\Storage;

use App\Domain\User\Repository\UserRepository;
use App\Renderer\RedirectRenderer;
use Odan\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

final class StorageAction
{
    private SessionInterface $session;
    private UserRepository $userRepository;

    private RedirectRenderer $renderer;
    private Twig $twig;

    public function __construct(
        SessionInterface $session, 
        UserRepository $userRepository, 
        RedirectRenderer $renderer,
        Twig $twig)
    {
        $this->session = $session;
        $this->userRepository = $userRepository;
        $this->renderer = $renderer;
        $this->twig = $twig;
    }

    public function pageBox(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        // if (!$this->bolSessionActive()) {
        //     return $this->renderer->redirect($response, '/login');
        // }

        return $this->twig->render(
            $response,
            'hloud/storage/box.twig',
            [
                'title' => 'hloudbin BOX',
                // 'session' => $this->session->get('hloudbin_userID'),
                // 'user' => (array)$this->userRepository->getUserById($this->session->get('hloudbin_userID')),
            ]
        );
    }

    public function bolSessionActive(): bool
    {
        return !empty($this->session->get('hloudbin_userID'));
    }
}