<?php

namespace App\Action\Hloud;

use App\Domain\User\Service\UserReader;
use Odan\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

final class HloudAction
{
    private SessionInterface $session;
    private UserReader $userReader;
    private Twig $twig;

    public function __construct(
        SessionInterface $sessionInterface,
        UserReader $userReader,
        Twig $twig
    ) {
        $this->session = $sessionInterface;
        $this->userReader = $userReader;
        $this->twig = $twig;
    }

    public function pageLanding(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $this->twig->render(
            $response,
            'hloud/landing.twig',
            [
                'title' => 'hloudBin',
                'slug' => 'b$ckup before migrate.',
                'session' => $this->session->get('hloudbin_userID'),
            ],
        );
    }

    public function pageAbout(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $this->twig->render(
            $response,
            'hloud/about.twig',
            ['title' => 'hloudBin About'],
        );
    }

    public function pageBlog(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $this->twig->render(
            $response,
            'hloud/blog.twig',
            ['title' => 'hloudBin Blog'],
        );
    }

    public function pageContact(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $this->twig->render(
            $response,
            'hloud/contact.twig',
            ['title' => 'hloudBin Contact'],
        );
    }

    public function pageTofS(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $this->twig->render(
            $response,
            'hloud/terms-of-service.twig',
            ['title' => 'hloudBin Terms of Service'],
        );
    }
}
