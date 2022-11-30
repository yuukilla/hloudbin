<?php

namespace App\Domain\Storage\Serivce;

use App\Domain\Storage\Repository\StorageRepository;
use App\Factory\LoggerFactory;
use Psr\Log\LoggerInterface;

final class StorageCreator
{
    private StorageRepository $repository;
    private LoggerInterface $logger;

    public function __construct(
        StorageRepository $storageRepository,
        LoggerFactory $loggerFactory
    ) {
        $this->repository = $storageRepository;
        $this->logger = $loggerFactory
            ->addFileHandler('storagefile_creator.log')
            ->createLogger();
    }

    public function createStorageFile(array $data): int
    {
        $storableId = $this->repository->createStorageFile($data);

        $this->logger->info(sprintf('Storage File created successfully: %s', $storableId));

        return $storableId;
    }
}