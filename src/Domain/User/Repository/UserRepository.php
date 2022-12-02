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
        return (int)$this->queryFactory
            ->newInsert('users', $this->toRow($user))
            ->execute()
            ->lastInsertId();
    }

    public function update(int $userId, array $user): void
    {
        $row = $this->toRow($user);

        $this->queryFactory->newUpdate('users', $row)
            ->where(['id' => $userId])
            ->execute();
    }

    public function delete(int $userId): void
    {
        $this->queryFactory->newDelete('users')
            ->where(['id' => $userId])
            ->execute();
    }

    public function getbyID(int $userId): array
    {
        $query = $this->queryFactory->newSelect('users');
        $query->select('*');
        $query->where(['id' => $userId]);

        $row = $query->execute()->fetch('assoc');

        if (!$row) {
            throw new DomainException(
                sprintf(
                    'User not found: %s',
                    $userId
                )
            );
        }

        return $row;
    }

    public function getbyName(string $userName): array
    {
        $query = $this->queryFactory->newSelect('users');
        $query->select('*');
        $query->where(['username' => $userName]);

        $row = $query->execute()->fetch('assoc');

        if (!$row) {
            throw new DomainException(
                sprintf(
                    'User not found: %s',
                    $userName
                )
            );
        }

        return $row;
    }

    public function existsID(int $userId): bool
    {
        $query = $this->queryFactory->newSelect('users');
        $query->select('id')->where(['id' => $userId]);

        return (bool)$query->execute()->fetch('assoc');
    }

    public function existsName(string $userName): bool
    {
        $query = $this->queryFactory->newSelect('users');
        $query->select('username')->where(['username' => $userName]);

        return (bool)$query->execute()->fetch('assoc');
    }

    public function existsEmail(string $userEmail): bool
    {
        $query = $this->queryFactory->newSelect('users');
        $query->select('email')->where(['email' => $userEmail]);

        return (bool)$query->execute()->fetch('assoc');
    }

    public function toRow(array $user): array
    {
        return [
            'username' => $user['username'],
            'firstname' => $user['firstname'],
            'lastname' => $user['lastname'],
            'avatar_id' => $user['avatar_id'],
            'email' => $user['email'],
            'password' => $user['password']
        ];
    }
}