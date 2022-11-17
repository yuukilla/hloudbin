<?php

namespace App\Action\User;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Renderer\JsonRenderer;

final class SignoutAction
{
    private JsonRenderer $jsonRenderer;

    public function __construct(JsonRenderer $jsonRenderer)
    {
        $this->jsonRenderer = $jsonRenderer;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $this->jsonRenderer
                ->json($response, ["resposne" => $response->getStatusCode()]);
    }
}