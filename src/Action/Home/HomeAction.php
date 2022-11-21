<?php

namespace App\Action\Home;


use App\Domain\User\Service\UserReader;
use Odan\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

final class HomeAction
{
    private Twig $twig;
    private SessionInterface $session;
    private UserReader $user;

    public function __construct(Twig $twig, SessionInterface $session, UserReader $user)
    {
        $this->twig = $twig;
        $this->session = $session;
        $this->user = $user;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $arrResData = [];
        if ( $this->session->get('user_id') != null ) {
            $user = $this->user->getById($this->session->get('user_id'));
            $arrResData = [
                'session' => $this->session->get('user_id'),
                'userid' => $user->id,
                'name' => $user->firstname . " " . $user->lastname,
            ];
        }

        $arrResDataNonSess = [
            'title' => 'hloudBin',
            'slug' => 'b$ackup before migrate.',
        ];


        return $this->twig->render(
            $response,
            'views/landing.twig',
            $arrResDataNonSess += $arrResData,
        );

    }
}
