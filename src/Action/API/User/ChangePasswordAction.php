<?php

namespace App\Action\API\User;

use App\Domain\User\Repository\UserRepository;
use App\Domain\User\Service\UserReader;
use App\Renderer\RedirectRenderer;
use Odan\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ChangePasswordAction
{
    private SessionInterface $session;

    private UserRepository $userRepository;
    private UserReader $userReader;

    private RedirectRenderer $redirectRenderer;

    public function __construct(
        SessionInterface $session,
        UserRepository $userRepository,
        UserReader $userReader,
        RedirectRenderer $redirectRenderer
    ) {
        $this->session = $session;
        $this->userRepository = $userRepository;
        $this->userReader = $userReader;
        $this->redirectRenderer = $redirectRenderer;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        if ($this->session->get('user_id') != null) {
            $objUser = $this->userReader->getById($this->session->get('user_id'));
            $arrData = (array)$request->getParsedBody();

            // Verify current password
            $bolPasswordVerified = password_verify($arrData['current_password'], $objUser->password);

            if (!$bolPasswordVerified) {
                $flash = $this->session->getFlash();
                $flash->add('error', 'Incorrect current password');

                return $this->redirectRenderer
                    ->redirect(
                        $response,
                        '/user'
                    );
            }

            // Change password
            $this->userRepository->update(
                $objUser->id,
                [
                    'password' => $arrData['newPassword'],
                ]
            );

            $flash = $this->session->getFlash();
            $flash->add('success', 'Password successfully updated');

            return $this->redirectRenderer
                ->redirect(
                    $response,
                    '/user'
                );
        }

        return $this->redirectRenderer
            ->redirect(
                $response,
                '/signin'
            );
    }
}
