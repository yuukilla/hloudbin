<?php

namespace App\Action\API\User;

use App\Domain\User\Repository\UserRepository;
use App\Domain\User\Service\UserReader;
use App\Renderer\RedirectRenderer;
use Odan\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class UpdateAccountAction
{
    private SessionInterface $session;

    private UserReader $userReader;

    private UserRepository $userRepository;

    private RedirectRenderer $redirectRenderer;

    public function __construct(
        SessionInterface $session,
        UserReader       $userReader,
        UserRepository   $userRepository,
        RedirectRenderer $redirectRenderer
    )
    {
        $this->session = $session;
        $this->userReader = $userReader;
        $this->userRepository = $userRepository;
        $this->redirectRenderer = $redirectRenderer;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {

        if (!$this->session->get('user_id')) {

            return $this->redirectRenderer->redirect(
                $response,
                '/signin'
            );
        }

        $objUser = $this->userReader->getById($this->session->get('user_id'));
        $arrData = (array)$request->getParsedBody();

        $this->userRepository->update($objUser->id, $arrData);

        return $this->redirectRenderer->redirect(
            $response,
            '/user'
        );
    }
}
