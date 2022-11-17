<?php

namespace App\Domain\User\Repository;

use App\Factory\QueryFactory;
use DomainException;

final class UserRepository
{
    private QueryFactory $queryFactory;

    public function __construct(QueryFactory $queryFactory)
    {
        $this->queryFactory = $queryFactory;
    }

    public function create(array $user): int
    {
        return (int)$this->queryFactory->newInsert('users', $this->toRow($user))
            ->execute()
            ->lastInsertId();
    }

    public function getById(int $userId): array
    {
        $query = $this->queryFactory->newSelect('users');
        $query->select(
            [
                'id',
                'username',
                'firstname',
                'lastname',
                'email',
                'date_joined',
                'date_updated',
            ]
        );
        $query->where(['id' => $userId]);

        $row = $query->execute()->fetch('assoc');

        if (!$row) {
            throw new DomainException(sprintf('User not found: %s', $userId));
        }

        return $row;
    }

    public function getByName(string $username): array
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
                'date_updated',
            ]
        );
        $query->where(['username' => $username]);

        $row = $query->execute()->fetch('assoc');

        if (!$row) {
            throw new DomainException(sprintf('User not found: %s', $username));
        }

        return $row;
    }

    public function update(int $userId, array $user): void
    {
        $row = $this->toRow($user);

        $this->queryFactory->newUpdate('users', $row)
            ->where(['id' => $userId])
            ->execute();
    }

    public function exists(int $userId): bool
    {
        $query = $this->queryFactory->newSelect('users');
        $query->select('id')->where(['id' => $userId]);

        return (bool)$query->execute()->fetch('assoc');
    }

    public function delete(int $userId): void
    {
        $this->queryFactory->newDelete('users')
            ->where(['id' => $userId])
            ->execute();
    }

    public function toRow(array $user): array
    {
        return [
            'username' => $user['suUsername'],
            'firstname' => $user['suFirstName'],
            'lastname' => $user['suLastName'],
            'email' => $user['suEmail'],
            'password' => password_hash($user['suPassword'], PASSWORD_DEFAULT),
        ];
    }
}