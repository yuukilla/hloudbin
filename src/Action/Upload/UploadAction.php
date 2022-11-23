<?php

namespace App\Action\Upload;

use App\Domain\User\Service\UserReader;
use App\Renderer\RedirectRenderer;
use Odan\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

final class UploadAction
{
    private SessionInterface $session;

    private UserReader $userReader;

    private Twig $twig;

    private RedirectRenderer $redirectRenderer;

    public function __construct(
        SessionInterface $session,
        UserReader $userReader,
        Twig $twig,
        RedirectRenderer $redirectRenderer
    ) {
        $this->session = $session;
        $this->userReader = $userReader;
        $this->twig = $twig;
        $this->redirectRenderer = $redirectRenderer;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        if ($this->session->get('user_id') != null) {
            $user = $this->userReader->getById($this->session->get('user_id'));

            return $this->twig->render(
                $response,
                'views/__upload/upload.twig',
                [
                    'session' => $this->session->get('user_id'),
                    'name' => $user->firstname . ' ' . $user->lastname,
                ]
            );
        }

        return $this->redirectRenderer->redirect(
            $response,
            '/signin'
        );
    }
}
