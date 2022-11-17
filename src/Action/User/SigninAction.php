<?php

namespace App\Action\User;

use App\Domain\User\Service\UserReader;
use App\Renderer\RedirectRenderer;
use App\Renderer\JsonRenderer;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class SigninAction
{
    private UserReader $reader;

    private RedirectRenderer $renderer;
    private JsonRenderer $jsonRenderer; /**  debug purposes only */

    public function __construct(UserReader $reader, RedirectRenderer $renderer, JsonRenderer $jsonRenderer)
    {
        $this->reader = $reader;
        $this->jsonRenderer = $jsonRenderer;
        $this->renderer = $renderer;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = (array)$request->getParsedBody();

        $user = $this->reader->getByUsername($data['siUsername']);
        $bolPasswordCorrect = password_verify($data['siPassword'], $user->password);

        if (!$bolPasswordCorrect) {
            return $this->jsonRenderer
                ->json($response, ["error" => "Incorrect password!"])
                ->withStatus(StatusCodeInterface::STATUS_UNAUTHORIZED);
        }

        /**
         * IMPLEMENT USER SESSION
         * RIGHT AT THIS PLACE.
         */

        return $this->jsonRenderer
            ->json($response, ["message" => "Successfully authorized"])
            ->withStatus(StatusCodeInterface::STATUS_OK);
    }
}