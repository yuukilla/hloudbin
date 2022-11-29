<?php

namespace App\Action\API\User;

use App\Domain\User\Repository\UserRepository;
use App\Renderer\JsonRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class UserAction
{
    private UserRepository $userRepository;
    private JsonRenderer $renderer;

    public function __construct(UserRepository $userRepository, JsonRenderer $renderer)
    {
        $this->userRepository = $userRepository;
        $this->renderer = $renderer;
    }

    public function actionByUsername(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        $username = $args['username'];

        $bolUsernameExists = (bool)$this->userRepository->usernameExists($username);

        return $this->renderer->json(
            $response,
            $bolUsernameExists,
        );
    }
    
    public function actionByEmail(
        ServerRequestInterface $request, 
        ResponseInterface $response, 
    ): ResponseInterface {
        $email = $request->getParsedBody()['email'];

        $bolEmailExists = (bool)$this->userRepository->emailExists($email);

        return $this->renderer->json(
            $response,
            $bolEmailExists,
        );
    }

}
