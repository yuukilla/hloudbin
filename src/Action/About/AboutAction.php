<?php

namespace App\Action\About;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Odan\Session\SessionInterface;
use Slim\Views\Twig;

final class AboutAction
{
    private Twig $twig;
    private SessionInterface $session;

    public function __construct(Twig $twig, SessionInterface $session)
    {
        $this->twig = $twig;
        $this->session = $session;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $this->twig->render(
            $response,
            'views/about.twig',
            [
                'title' => "hloudBin: About",
                "text" => "placeholder",
            ],
        );
    }
}