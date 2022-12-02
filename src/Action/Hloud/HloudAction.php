<?php

namespace App\Action\Hloud;

use App\Domain\User\Service\UserReader;
use Odan\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

final class HloudAction
{
    private SessionInterface $sessionInterface;
    private UserReader $userReader;
    private Twig $twigRenderer;

    /**
     * Summary of __construct
     * @param SessionInterface $sessionInterface
     * @param UserReader $userReader
     * @param Twig $twigRenderer
     */
    public function __construct(
        SessionInterface $sessionInterface,
        UserReader $userReader,
        Twig $twigRenderer
    ) {
        $this->sessionInterface = $sessionInterface;
        $this->userReader = $userReader;
        $this->twigRenderer = $twigRenderer;
    }

    /**
     * Summary of pageLanding
     * 
     * Check fi session is active and
     * render template from 'hloud/' path
     * with necessary data
     * 
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function pageLanding(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {

        if ( !$this->sessionInterface->get('hloudbin_userID') != null ) {
            return $this->twigRenderer->render(
                $response,
                'hloud/landing.twig',
                [
                    'title' => 'hloudBin',
                    'slug' => 'b$ckup before migrate.',
                ]
            );
        }

        $user = $this->userReader->getbyID(
            $this->sessionInterface->get('hloudbin_userID')
        );

        return $this->twigRenderer->render(
            $response,
            'hloud/landing.twig',
            [
                'title' => 'hloudBin',
                'slug' => 'b$ckup before migrate.',
                'session' => $this->sessionInterface->get('hloudbin_userID'),
                'user' => (array) $user
            ]
        );

    }
}