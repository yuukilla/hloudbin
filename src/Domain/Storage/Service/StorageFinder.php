<?php

namespace App\Domain\Storage\Serivce;

use App\Domain\Storage\Data\StorageFinderItem;
use App\Domain\Storage\Data\StorageFinderResult;
use App\Domain\Storage\Repository\StorageFinderRepository;

final class StorageFinder
{
    private StorageFinderRepository $repository;

    public function __construct(StorageFinderRepository $storageFinderRepository)
    {
        $this->repository = $storageFinderRepository;
    }

    public function find(): StorageFinderResult
    {
        $storables = $this->repository->find();

        return $this->result($storables);
    }

    public function result(array $storableRows): StorageFinderResult
    {
        $result = new StorageFinderResult();

        foreach($storableRows as $storableRow) {
            $storable = new StorageFinderItem();
            $storable->id = $storableRow['id'];
            $storable->user_id = $storableRow['user_id'];
            $storable->type = $storableRow['type'];
            $storable->name = $storableRow['name'];
            $storable->visible = $storableRow['visible'];
            $storable->upload_date = $storableRow['upload_date'];

            $result->files[] = $storable;
        }

        return $result;
    }
}