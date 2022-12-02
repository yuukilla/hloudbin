<?php

namespace App\Domain\Storage\Repository;

use App\Factory\QueryFactory;
use DomainException;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\UploadedFileInterface;

class StorageRepository
{
    private QueryFactory $queryFactory;
    private ContainerInterface $container;
    private UploadedFileInterface $uploadedFile;


    public function __construct(
        QueryFactory $queryFactory,
        ContainerInterface $containerInterface,
    ) {
        $this->queryFactory = $queryFactory;
        $this->container = $containerInterface;
    }

    public function create($storable): int
    {
        $settings = $this->container->get('settings');
        $directory = $settings['files']['upload_directory'];

        $uplData = $this->moveUploadedFile($directory, $storable);
        $data = [
            'user_id' => 1,
            'type' => $uplData['fileExt'],
            'filename' => $uplData['fileName'],
            'basename' => $uplData['baseName'],
            'visible' => 1
        ];

        return (int)$this->queryFactory
            ->newInsert('`storage.files`', $this->toRow($data))
            ->execute()
            ->lastInsertId();
    }

    public function toRow(array $storable): array
    {
        return [
            'user_id' => $storable['user_id'],
            'type' => $storable['type'],
            'filename' => $storable['filename'],
            'basename' => $storable['basename'],
            'visible' => $storable['visible']
        ];
    }

    public function moveUploadedFile(string $dir, UploadedFileInterface $uploadedFile)
    {
        $fileExt = pathinfo(
            $uploadedFile->getClientFilename(),
            PATHINFO_EXTENSION
        );
        $baseName = bin2hex(random_bytes(16));
        $fileName = sprintf('%s.%0.8s', $baseName, $fileExt);

        $uploadedFile->moveTo($dir.DIRECTORY_SEPARATOR.$fileName);

        return [
            'fileExt' => $fileExt,
            'fileName' => pathinfo($uploadedFile->getClientFilename(), PATHINFO_BASENAME),
            'baseName' => $fileName
        ];
    }
}
