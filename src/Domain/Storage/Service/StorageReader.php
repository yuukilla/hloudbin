<?php

namespace App\Domain\Storage\Serivce;

use App\Domain\Storage\Data\StorageReaderResult;
use App\Domain\Storage\Repository\StorageRepository;

final class StorageReader
{
    private StorageRepository $storageRepository;

    public function __construct(StorageRepository $storageRepository)
    {
        $this->storageRepository = $storageRepository;
    }

    public function getStorageFile(int $storableId): StorageReaderResult
    {
        $storageRow = $this->storageRepository->getFileById($storableId);

        $result = new StorageReaderResult();
        $result->id = $storageRow['id'];
        $result->user_id = $storageRow['user_id'];
        $result->type = $storageRow['type'];
        $result->name = $storageRow['name'];
        $result->visible = $storageRow['visible'];
        $result->upload_date = $storageRow['upload_date'];

        return $result;
    }
}