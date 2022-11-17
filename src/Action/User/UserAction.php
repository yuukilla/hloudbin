<?php

namespace App\Action\User;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Renderer\JsonRenderer;

final class UserAction
{
    private JsonRenderer $jsonRenderer;

    public function __construct(JsonRenderer $jsonRenderer)
    {
        $this->jsonRenderer = $jsonRenderer;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $this->jsonRenderer
                ->json($response, ["response" => $response->getStatusCode()]);
    }
}