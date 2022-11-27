<?php

namespace App\Domain\User\Repository;
use App\Factory\QueryFactory;

final class UserFinderRepository
{
    private QueryFactory $queryFactory;

    public function __construct(QueryFactory $queryFactory)
    {
        $this->queryFactory = $queryFactory;
    }

    public function find(): array
    {
        $query = $this->queryFactory->newSelect('users');

        $query->select(
            [
                'id',
                'username',
                'firstname',
                'lastname',
                'email',
                'password',
                'date_joined',
                'date_updated'
            ]
        );

        return $query->execute()->fetchAll('assoc') ?: [];
    }
}
