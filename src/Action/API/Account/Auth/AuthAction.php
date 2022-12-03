<?php

namespace App\Action\API\Account\Auth;

use App\Domain\User\Repository\UserRepository;
use App\Domain\User\Service\UserCreator;
use App\Domain\User\Service\UserReader;
use App\Renderer\JsonRenderer;
use App\Renderer\RedirectRenderer;
use Odan\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class AuthAction
{
    private UserCreator $userCreator;
    private UserReader $userReader;
    private UserRepository $userRepository;
    private SessionInterface $sessionInterface;
    private RedirectRenderer $redirectRenderer;
    private JsonRenderer $jsonRenderer;
    
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
        UserRepository $userRepository,
        SessionInterface $sessionInterface,
        RedirectRenderer $redirectRenderer,
        JsonRenderer $jsonRenderer
    ) {
        $this->userCreator = $userCreator;
        $this->userReader = $userReader;
        $this->userRepository = $userRepository;
        $this->sessionInterface = $sessionInterface;
        $this->redirectRenderer = $redirectRenderer;
        $this->jsonRenderer = $jsonRenderer;
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
     * Summary of existsUsername
     * 
     * Check if username is already claimed
     * and return boolean value
     * 
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function existsUsername(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        $username = $request->getQueryParams()['search'];
        $bolUsernameExists = (bool) $this->userRepository->existsName($username);

        return $this->jsonRenderer->json(
            $response,
            $bolUsernameExists
        );
    }

    /**
     * Summary of existsEmail
     * 
     * Check if email is already claimed
     * and return boolean
     * 
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function existsEmail(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        $email = $request->getQueryParams()['search'];
        $bolEmailExists = (bool) $this->userRepository->existsEmail($email);

        return $this->jsonRenderer->json(
            $response,
            $bolEmailExists
        );
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