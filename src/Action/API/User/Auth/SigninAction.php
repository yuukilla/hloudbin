<?php

namespace App\Action\API\User\Auth;

use App\Domain\User\Service\UserReader;
use App\Renderer\RedirectRenderer;
use Fig\Http\Message\StatusCodeInterface;
use Odan\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class SigninAction
{
    private UserReader $reader;

    private SessionInterface $session;

    private RedirectRenderer $renderer;

    public function __construct(UserReader $reader, SessionInterface $session, RedirectRenderer $renderer)
    {
        $this->reader = $reader;
        $this->session = $session;
        $this->renderer = $renderer;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $arrData = (array)$request->getParsedBody();

        try {
            $objUser = $this->reader->getByUsername($arrData['siUsername']);

            $bolPasswordVerified = password_verify($arrData['siPassword'], $objUser->password);

            if (!$bolPasswordVerified) {
                $flash = $this->session->getFlash();
                $flash->add('error', 'Incorrect username and/or password');
                return $this->renderer
                    ->redirect($response, '/signin');
            }

            $this->session->set('user_id', $objUser->id);
            $this->session->save();

            return $this->renderer
                ->redirect($response, '/');

        } catch (\Exception $exception) {
            $flash = $this->session->getFlash();
            $flash->add('error', $exception->getMessage());

            return $this->renderer
                ->redirect($response, '/signin');
        }
    }
}