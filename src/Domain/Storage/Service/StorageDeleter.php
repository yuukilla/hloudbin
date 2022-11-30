<?php

namespace App\Domain\Storage\Serivce;

use App\Domain\Storage\Repository\StorageRepository;
use App\Factory\LoggerFactory;
use Psr\Log\LoggerInterface;

final class StorageDeleter
{
    private StorageRepository $repository;
    private LoggerInterface $logger;

    public function __construct(
        StorageRepository $storageRepository,
        LoggerFactory $loggerFactory
    ) {
        $this->repository = $storageRepository;
        $this->logger = $loggerFactory
            ->addFileHandler('storagefile_deleter.log')
            ->createLogger();
    }

    public function deleteStorageFile(int $storableId): void
    {
        $this->repository-delete($storableId);

        $this->logger->info(sprintf('Storage file delete successfully: %s', $storableId));
    }
}
