<?php

namespace App\Action\TermsofService;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

final class TermsofServiceAction
{
    private Twig $twig;

    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $this->twig->render(
            $response,
            'views/termsofservice.twig',
            [
                'title' => 'hloudBind : Terms of Serivce',
                'text' => 'some placeholder text right here',
            ]
        );
    }
}
