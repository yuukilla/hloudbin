<?php

namespace App\Action\User;

use App\Domain\User\Service\UserCreator;
use App\Renderer\RedirectRenderer;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class SignupAction
{
    private RedirectRenderer $renderer;

    private UserCreator $creator;

    public function __construct(UserCreator $creator, RedirectRenderer $renderer)
    {
        $this->creator = $creator;
        $this->renderer = $renderer;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = (array)$request->getParsedBody();

        $userId = $this->creator->create($data); // JUST CREATES USER RECORD IN DATABASE;

        /**
         * IMPLEMENT USER SESSION
         * RIGHT AT THIS PLACE.
         */

        return $this->renderer->redirect($response, '/');

    }
}