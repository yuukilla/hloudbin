<?php

namespace App\Action\API\Storage;

use App\Domain\Storage\Repository\StorageRepository;
use App\Renderer\JsonRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class StorageAction
{
    private StorageRepository $storageRepository;
    private JsonRenderer $jsonRenderer;

    public function __construct(
        StorageRepository $storageRepository,
        JsonRenderer $jsonRenderer
    ) {
        $this->storageRepository = $storageRepository;
        $this->jsonRenderer = $jsonRenderer;
    }

    /**
     * Summary of upload
     * 
     * Upload file to database and asign it to user
     * ONLY ON TEST PURPOSES
     * WORK IN PROGRESS
     * 
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function upload(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        $formFiles = $request->getUploadedFiles();
        $formFile = $formFiles['file1'];

        if ($formFile->getError() === UPLOAD_ERR_OK) {
            $fileID = $this->storageRepository->create($formFile);
        }

        return $this->jsonRenderer->json(
            $response,
            [
                'info' => sprintf('File uploaded successfully: %s', $fileID),
                'filename' => $formFile->getClientFilename()
            ]
        );
    }
}