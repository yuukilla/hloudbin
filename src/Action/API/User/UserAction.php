<?php

namespace App\Action\API\User;

use App\Domain\User\Repository\UserRepository;
use App\Renderer\JsonRenderer;
use App\Renderer\RedirectRenderer;
use Fig\Http\Message\StatusCodeInterface;
use Odan\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
// use Psr\Http\Message\UploadedFileInterface;
use Psr\Container\ContainerInterface;

final class UserAction
{
    // private ContainerInterface $container;
    private SessionInterface $session;
    private UserRepository $userRepository;
    private JsonRenderer $renderer;
    private RedirectRenderer $redRenderer;

    public function __construct(
        // ContainerInterface $container,
        SessionInterface $session,
        UserRepository $userRepository,
        JsonRenderer $renderer,
        RedirectRenderer $redRenderer
    ) {
        // $this->container = $container;
        $this->session = $session;
        $this->userRepository = $userRepository;
        $this->renderer = $renderer;
        $this->redRenderer = $redRenderer;
    }

    public function actionByUsername(
        ServerRequestInterface $req,
        ResponseInterface $res,
        array $args
    ): ResponseInterface {
        $username = $args['username'];

        $bolUsernameExists = (bool)$this->userRepository->usernameExists($username);

        return $this->renderer->json(
            $res,
            $bolUsernameExists,
        );
    }
    
    public function actionByEmail(
        ServerRequestInterface $req, 
        ResponseInterface $res, 
    ): ResponseInterface {
        $email = $req->getParsedBody()['email'];

        $bolEmailExists = (bool)$this->userRepository->emailExists($email);

        return $this->renderer->json(
            $res,
            $bolEmailExists,
        );
    }

    public function actionUpdateAccount(
        ServerRequestInterface $req,
        ResponseInterface $res
    ): ResponseInterface {
        $arrData = $req->getParsedBody();
        $currUser = $this->userRepository->getUserById($this->session->get('hloudbin_userID'));

        // $dir = $this->container->get('settings')['files']['upload_directory'];
        // $uplFiles = $req->getUploadedFiles();

        // $uplFile = $uplFiles['avatar'];

        // var_dump($uplFiles);
        // if ( $uplFile->getError() === UPLOAD_ERR_OK ) {
        //     $fileName = $this->moveUploadedFile($dir, $uplFile);
        // }

        try {
            $data = [
                // 'avatar' => $fileName,
                'username' => $arrData['username'],
                'firstname' => $arrData['firstname'],
                'lastname' => $arrData['lastname'],
                'email' => $arrData['email'],
                'password' => $currUser['password'],
            ];
            $this->userRepository->updateUser(
                $this->session->get('hloudbin_userID'),
                $data
            );
    
            return $this->renderer->json(
                $res,
                [
                    'status' => StatusCodeInterface::STATUS_OK,
                    'message' => 'Account data successfully updated'
                ]
            );
        } catch (\Throwable $th) {
            return $this->renderer->json(
                $res,
                [
                    'status' => $th->getCode(),
                    'message' => 'Something went wrong.',
                    'error' => $th->getMessage()
                ]
            );
        }   
    }

    // public function moveUploadedFile(string $directory, UploadedFileInterface $uploadedFile)
    // {
    //     $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);

    //     $baseName = bin2hex(random_bytes(16));
    //     $fileName = sprintf('%s.%0.8s', $baseName, $extension);

    //     $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $fileName);

    //     return $fileName;
    // }
}
