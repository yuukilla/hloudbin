<?php

namespace App\Action\Hloud\Account;

use App\Domain\User\Service\UserReader;
use App\Renderer\RedirectRenderer;
use Odan\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

final class AccountAction
{
    private SessionInterface $sessionInterface;
    private UserReader $userReader;
    private RedirectRenderer $redirectRenderer;
    private Twig $twigRenderer;

    /**
     * Summary of __construct
     * @param SessionInterface $sessionInterface
     * @param UserReader $userReader
     * @param RedirectRenderer $redirectRenderer
     * @param Twig $twigRenderer
     */
    public function __construct(
        SessionInterface $sessionInterface,
        UserReader $userReader,
        RedirectRenderer $redirectRenderer,
        Twig $twigRenderer
    ) {
        $this->sessionInterface = $sessionInterface;
        $this->userReader = $userReader;
        $this->redirectRenderer = $redirectRenderer;
        $this->twigRenderer = $twigRenderer;
    }
    
    /**
     * Summary of pageSignin
     * 
     * Check if session is already active
     * and render template or redirect
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function pageSignin(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {

        if ($this->bolSessionActive()) {
            return $this->redirectRenderer->redirect(
                $response,
                '/account'
            );
        }

        return $this->twigRenderer->render(
            $response,
            'hloud/auth/signin.twig',
            [
                'title' => 'hloudBin Signin',
                'hideModalLink' => true,
            ]
        );
    }

    /**
     * Summary of pageSignup
     * 
     * Check if session active
     * and render template or redirect
     * 
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function pageSignup(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        if ($this->bolSessionActive()) {
            return $this->redirectRenderer->redirect($response, '/account');
        }

        return $this->twigRenderer->render(
            $response,
            'hloud/auth/signup.twig',
            [
                'title' => 'hloudBin Signup',
                'hideModalLink' => true
            ]
        );
    }

    public function pageAccount(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        if (!$this->bolSessionActive()) { 
            return $this->redirectRenderer->redirect($response, '/login');
        }

        $user = $this->userReader->getbyID(
            $this->sessionInterface->get('hloudbin_userID')
        );

        return $this->twigRenderer->render(
            $response,
            'hloud/account/account.twig',
            [
                'title' => 'hloudBin Account',
                'session' => $this->sessionInterface->get('hloudbin_userID'),
                'user' => (array) $user
            ]
        );
    }

    /**
     * Summary of bolSessionActive
     * 
     * Get boolean value if
     * session is active or not
     * 
     * @return bool
     */
    public function bolSessionActive(): bool
    {
        return !empty($this->sessionInterface->get('hloudbin_userID'));
    }
}
