<?php

namespace App\Action\API\Account;

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
    private SessionInterface $sessionInterface;
    private UserRepository $userRepository;
    private StorageRepository $storageRepository;
    private JsonRenderer $jsonRenderer;
    private RedirectRenderer $redirectRenderer;

    /**
     * Summary of __construct
     * @param SessionInterface $sessionInterface
     * @param UserRepository $userRepository
     * @param StorageRepository $storageRepository
     * @param JsonRenderer $jsonRenderer
     * @param RedirectRenderer $redirectRenderer
     */
    public function __construct(
        SessionInterface $sessionInterface,
        UserRepository $userRepository,
        StorageRepository $storageRepository,
        JsonRenderer $jsonRenderer,
        RedirectRenderer $redirectRenderer
    ) {
        $this->sessionInterface = $sessionInterface;
        $this->userRepository = $userRepository;
        $this->storageRepository = $storageRepository;
        $this->jsonRenderer = $jsonRenderer;
        $this->redirectRenderer = $redirectRenderer;
    }

    /**
     * Summary of bolUsernameExists
     * 
     * Check if username is already used
     * and return boolean value
     * 
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function bolUsernameExists(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {

        return $this->jsonRenderer->json(
            $response,
            (bool) $this->userRepository->existsName($args['username'])
        );
    }

    /**
     * Summary of bolEmailExists
     * 
     * Check if email is already used
     * and return boolean value
     * 
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return ResponseInterface
     */
    public function bolEmailExists(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {

        return $this->jsonRenderer->json(
            $response,
            (bool) $this->userRepository->existsEmail($args['email'])
        );
    }

    /**
     * Summary of updateAccount
     * 
     * Update Account data in database and if available
     * upload and set profile picture image 
     * 
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function updateAccount(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {

        $formData = $request->getParsedBody();
        $formFile = $request->getUploadedFiles()['avatar'];

        $currentAccount = $this->userRepository->getbyID(
            $this->sessionInterface->get('hloudbin_userID')
        );

        if ($formFile->getError() === UPLOAD_ERR_OK) {
            $imageID = $this->storageRepository->create($formFile);
        }

        try {
            $data = [
                'username' => $formData['username'],
                'firstname' => $formData['firstname'],
                'lastname' => $formData['lastname'],
                'email' => $formData['email'],
                'avatar_id' => $imageID,
                'password' => $currentAccount['password']
            ];
            
            $this->userRepository->update($currentAccount['id'], $data);

            return $this->jsonRenderer->json(
                $response,
                [
                    'status' => StatusCodeInterface::STATUS_OK,
                    'message' => 'Account data successfully updated'
                ]
            );
        } catch (\Throwable $throwable) {
            return $this->jsonRenderer->json(
                $response,
                [
                    'status' => $throwable->getCode(),
                    'message' => $throwable->getMessage()
                ]
            );
        }
    }
}