<?php

namespace App\Action\API\Storage;

use App\Domain\Storage\Repository\StorageRepository;
use App\Renderer\JsonRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class StorageAction
{
    private StorageRepository $repository;
    private JsonRenderer $renderer;

    public function __construct(
        StorageRepository $storageRepository,
        JsonRenderer $jsonRenderer
    ) {
        $this->repository = $storageRepository;
        $this->renderer = $jsonRenderer;
    }

    public function upload(ServerRequestInterface $req, ResponseInterface $res): ResponseInterface
    {
        $formFiles = $req->getUploadedFiles();
        $formFile = $formFiles['file1'];

        if ( $formFile->getError() === UPLOAD_ERR_OK ) {
            $fileID = $this->repository->create($formFile);
        }

        return $this->renderer->json(
            $res,
            [
                'info' => sprintf('File inserted successfully: %s', $fileID),
                'formFile' => $formFile->getClientFilename()
            ]
        );
    }
}