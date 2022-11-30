<?php

namespace App\Domain\Storage\Repository;

use App\Factory\QueryFactory;
use DomainException;

final class StorageRepository
{
    private QueryFactory $queryFactory;

    public function __construct(QueryFactory $queryFactory)
    {
        $this->queryFactory = $queryFactory;
    }

    public function createStorageFile(array $storable): int
    {
        return (int)$this->queryFactory->newInsert('storage.files', $this->toRow($storable))
            ->execute()
            ->lastInsertId();
    }

    public function deleteStorageFile(int $storableId): void
    {
        $this->queryFactory->newDelete('storage.files')
            ->where(['id' => $storableId])
            ->execute();
    }

    public function getFileById(int $storableId): array
    {
        $query = $this->queryFactory->newSelect('storage.files');
        $query->select([
            'id',
            'user_id',
            'type',
            'name',
            'visible',
            'upload_date'
        ]);
        $query->where(['id' => $storableId]);

        $row = $query->execute()->fetch('assoc');

        if (!$row) {
            throw new DomainException(sprintf('File not found: %s', $storableId));
        }

        return $row;
    }

    public function toRow(array $storable): array
    {
        return [
            'user_id' => $storable['user_id'],
            'type' => $storable['type'],
            'name' => $storable['name'],
            'visible' => $storable['visible'],
        ];
    }
}