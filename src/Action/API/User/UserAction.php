<?php

namespace App\Action\API\User;

use App\Domain\Storage\Repository\StorageRepository;
use App\Domain\User\Repository\UserRepository;
use App\Renderer\JsonRenderer;
use App\Renderer\RedirectRenderer;
use Fig\Http\Message\StatusCodeInterface;
use Odan\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class UserAction
{
    private SessionInterface $session;
    private UserRepository $userRepository;
    private StorageRepository $storageRepository;
    private JsonRenderer $renderer;
    private RedirectRenderer $redRenderer;

    public function __construct(
        SessionInterface $session,
        UserRepository $userRepository,
        StorageRepository $storageRepository,
        JsonRenderer $renderer,
        RedirectRenderer $redRenderer
    ) {
        $this->session = $session;
        $this->userRepository = $userRepository;
        $this->storageRepository = $storageRepository;
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

        
        $formData = $req->getParsedBody();
        $formFile = $req->getUploadedFiles()['avatar'];

        $sessionUserID = $this->session->get('hloudbin_userID');
        $currAccount = $this->userRepository->getUserById($sessionUserID);

        if ( $formFile->getError() === UPLOAD_ERR_OK ) {
            // $fileData = $this->($directory, $formFile);
            // $fileID = $this->storageRepository->createStorageFile($formFile);
        }

        try {
            $data = [
                'username' => $formData['username'],
                'firstname' => $formData['firstname'],
                'lastname' => $formData['lastname'],
                'email' => $formData['email'],
                // 'avatar_id' => $fileID,
                'password' => $currAccount['password']
            ];
            $this->userRepository->updateUser($currAccount['id'], $data);

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
                    'message' => $th->getMessage()
                ]
            );
        }


    }
    // public function actionUpdateAccount(
    //     ServerRequestInterface $req,
    //     ResponseInterface $res
    // ): ResponseInterface {

    //     $arrData = $req->getParsedBody();
    //     $currUser = $this->userRepository->getUserById($this->session->get('hloudbin_userID'));

       

    //     $settings = $this->container->get('settings');
    //     $directory = $settings['files']['upload_directory'];

        

    //     try {
    //         $data = [
    //             // 'avatar' => $fileName,
    //             'username' => $arrData['username'],
    //             'firstname' => $arrData['firstname'],
    //             'lastname' => $arrData['lastname'],
    //             'email' => $arrData['email'],
    //             'password' => $currUser['password'],
    //         ];
    //         $this->userRepository->updateUser(
    //             $this->session->get('hloudbin_userID'),
    //             $data
    //         );
    
    //         return $this->renderer->json(
    //             $res,
    //             [
    //                 'status' => StatusCodeInterface::STATUS_OK,
    //                 'message' => 'Account data successfully updated'
    //             ]
    //         );
    //     } catch (\Throwable $th) {
    //         return $this->renderer->json(
    //             $res,
    //             [
    //                 'status' => $th->getCode(),
    //                 'message' => 'Something went wrong.',
    //                 'error' => $th->getMessage()
    //             ]
    //         );
    //     }   
    // }

    

     // $dir = $this->container->get('settings')['files']['upload_directory'];
        // $uplFiles = $req->getUploadedFiles();

        // $uplFile = $uplFiles['avatar'];

        // var_dump($uplFiles);
        // if ( $uplFile->getError() === UPLOAD_ERR_OK ) {
        //     $fileName = $this->moveUploadedFile($dir, $uplFile);
        // }
}
