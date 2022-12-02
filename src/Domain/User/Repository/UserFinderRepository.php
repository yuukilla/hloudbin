<?php

namespace App\Domain\User\Repository;

use App\Factory\QueryFactory;

final class UserFinderRepository
{
    private QueryFactory $queryFactory;

    public function __construct(
        QueryFactory $queryFactory
    ) {
        $this->queryFactory = $queryFactory;
    }

    public function find(): array
    {
        $query = $this->queryFactory->newSelect('users');

        $query->select('*');

        return $query->execute()->fetchAll('assoc') ?: [];
    }
}