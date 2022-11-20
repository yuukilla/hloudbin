<?php

namespace App\Action\User;

use App\Domain\User\Service\UserReader;
use App\Renderer\RedirectRenderer;
use App\Renderer\JsonRenderer;
use Exception;
use Fig\Http\Message\StatusCodeInterface;
use Odan\Session\PhpSession;
use DomainException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class SigninAction
{
    private UserReader $reader;

    private RedirectRenderer $renderer;

    private PhpSession $session;
    private JsonRenderer $jsonRenderer; /**  debug purposes only */

    public function __construct(UserReader $reader, RedirectRenderer $renderer, JsonRenderer $jsonRenderer, PhpSession $session)
    {
        $this->reader = $reader;
        $this->session = $session;
        $this->jsonRenderer = $jsonRenderer;
        $this->renderer = $renderer;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = (array)$request->getParsedBody();

        try {
            $user = $this->reader->getByUsername($data['siUsername']);

            $bolPasswordCorrect = password_verify($data['siPassword'], $user->password);

            if ( !$bolPasswordCorrect ) {
                $this->session->getFlash()->add('error', 'incorrect username and/or password');
                return $this->renderer->redirect($response, '/');
            }

            $this->session->set('userid', $user->id);
            $this->session->save();

            return $this->renderer->redirect($response, '/');

        } catch (Exception $e) {
            $this->session->getFlash()->add('error', 'incorrect username and/or password');
            return $this->renderer->redirect($response, '/');
        }


//
//        $this->jsonRenderer
//            ->json($response, $user);
//        if (  ) {
//            $this->jsonRenderer
//                ->json($response, ['error' => "user not found"])
//                ->withStatus(StatusCodeInterface::STATUS_NOT_FOUND);
//        }

//        $bolPasswordCorrect = password_verify($data['siPassword'], $user->password);
//
//        if (!$bolPasswordCorrect) {
//            return $this->jsonRenderer
//                ->json($response, ["error" => "Incorrect password!"])
//                ->withStatus(StatusCodeInterface::STATUS_UNAUTHORIZED);
//        }



        /**
         * IMPLEMENT USER SESSION
         * RIGHT AT THIS PLACE.
         */


//        return $this->jsonRenderer
//            ->json($response, ["message" => "Successfully authorized"])
//            ->withStatus(StatusCodeInterface::STATUS_OK);
    }
}