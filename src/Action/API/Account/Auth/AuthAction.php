<?php

namespace App\Action\API\Account\Auth;

use App\Domain\User\Service\UserCreator;
use App\Domain\User\Service\UserReader;
use App\Renderer\RedirectRenderer;
use Odan\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class AuthAction
{
    private UserCreator $userCreator;
    private UserReader $userReader;
    private SessionInterface $sessionInterface;
    private RedirectRenderer $redirectRenderer;
    
    /**
     * Summary of __construct
     * @param UserCreator $userCreator
     * @param UserReader $userReader
     * @param SessionInterface $sessionInterface
     * @param RedirectRenderer $redirectRenderer
     */
    public function __construct(
        UserCreator $userCreator,
        UserReader $userReader,
        SessionInterface $sessionInterface,
        RedirectRenderer $redirectRenderer
    ) {
        $this->userCreator = $userCreator;
        $this->userReader = $userReader;
        $this->sessionInterface = $sessionInterface;
        $this->redirectRenderer = $redirectRenderer;
    }
    
    /**
     * Summary of signupCall
     * 
     * Create new user and set session
     * if somehow passwords does not match
     * add flash message and redirect to signup route
     * 
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function signupCall(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = (array) $request->getParsedBody();

        if ($data['password'] !== $data['password-repeat']) {
            $flash = $this->sessionInterface->getFlash();
            $flash->add('error', 'Passwords doesn\'t match.');
            
            return $this->redirectRenderer->redirect($response, '/signup');
        }

        $userId = $this->userCreator->create($data);

        $this->sessionInterface->set('hloudbin_userID', $userId);
        $this->sessionInterface->save();

        return $this->redirectRenderer->redirect($response, '/');
    }


    /**
     * Summary of signinCall
     * 
     * Get userdata by username and verify password
     * if password is correct then set session
     * else add flash message and redirect to login route
     * 
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function signinCall(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = (array) $request->getParsedBody();

        try {
            $user = $this->userReader->getbyName($data['username']);

            $bolPasswordVerified = password_verify($data['password'], $user->password);

            if (!$bolPasswordVerified) {
                $flash = $this->sessionInterface->getFlash();
                $flash->add('error', 'Incorrect username and/or password');

                return $this->redirectRenderer->redirect($response, '/login');
            }

            $this->sessionInterface->set('hloudbin_userID', $user->id);
            $this->sessionInterface->save();

            return $this->redirectRenderer->redirect($response, '/');
        } catch (\Exception $exception) {
            $flash = $this->sessionInterface->getFlash();
            $flash->add('error', $exception->getMessage());

            return $this->redirectRenderer->redirect($response, '/login');
        }
    }

    /**
     * Summary of signoutCall
     * 
     * Destroy session and redirect to login route
     * 
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function signoutCall(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $this->sessionInterface->destroy();

        return $this->redirectRenderer->redirect($response, '/login');
    }
}