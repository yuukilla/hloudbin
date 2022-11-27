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

    public function createUser(array $user): int
    {
        return (int)$this->queryFactory->newInsert('users', $this->toRow($user))
            ->execute()
            ->lastInsertId();
    }

    public function updateUser(int $userId, array $user): void
    {
        $row = $this->toRow($user);

        $this->queryFactory->newUpdate('users', $row)
            ->where(['id' => $userId])
            ->execute();
    }

    public function userDelete(int $userId): void
    {
        $this->queryFactory->newDelete('users')
            ->where(['id' => $userId])
            ->execute();
    }

    public function getUserById(int $userId): array
    {
        $query = $this->queryFactory->newSelect('users');
        $query->select([
            'id',
            'username',
            'firstname',
            'lastname',
            'email',
            'password',
            'date_joined',
            'date_updated',
        ]);
        $query->where(['id' => $userId]);

        $row = $query->execute()->fetch('assoc');

        if (!$row) {
            throw new DomainException(sprintf('User not found: %s', $userId));
        }

        return $row;
    }

    public function getUserByName(string $userName): array
    {
        $query = $this->queryFactory->newSelect('users');
        $query->select([
            'id',
            'username',
            'firstname',
            'lastname',
            'email',
            'password',
            'date_joined',
            'date_updated',
        ]);
        $query->where(['username' => $userName]);

        $row = $query->execute()->fetch('assoc');

        if (!$row) {
            throw new DomainException(sprintf('User not found: %s', $userName));
        }

        return $row;
    }

    public function userExists(int $userId): bool
    {
        $query = $this->queryFactory->newSelect('users');
        $query->select('id')->where(['id'=> $userId]);

        return (bool)$query->execute()->fetch('assoc');
    }

    public function toRow(array $user): array
    {
        return [
            'username' => $user['username'],
            'firstname' => $user['firstname'],
            'lastname' => $user['lastname'],
            'email' => $user['email'],
            'password' => password_hash($user['password'], PASSWORD_DEFAULT),
        ];
    }
}
