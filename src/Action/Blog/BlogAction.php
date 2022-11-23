<?php

namespace App\Action\Blog;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

final class BlogAction
{
    private Twig $twig;

    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $this
            ->twig
            ->render(
                $response,
                'views/blog.twig',
                [
                    'title' => 'hloudBin: Blog',
                    'placeholder' => 'placeholder text',
                ],
            );
    }
}
