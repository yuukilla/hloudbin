<?php

namespace App\Domain\Storage\Repository;

use App\Factory\QueryFactory;

final class StorageFinderRepository
{
    private QueryFactory $queryFactory;

    public function __construct(QueryFactory $queryFactory)
    {
        $this->queryFactory = $queryFactory;
    }

    public function find(): array
    {
        $query = $this->queryFactory->newSelect('storage.files');

        $query->select(
            [
                'id',
                'user_id',
                'type',
                'name',
                'visible',
                'upload_date'
            ]
        );

        return $query->execute()->fetchAll('assoc') ?: [];
    }
}
