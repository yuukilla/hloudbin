<?php

namespace App\Domain\User\Service;

use App\Domain\User\Data\UserReaderResult;
use App\Domain\User\Repository\UserRepository;

final class UserReader
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getById(int $userId): UserReaderResult
    {
        $userRow = $this->repository->getById($userId);

        $result = new UserReaderResult();
        $result->id = $userRow['id'];
        $result->username = $userRow['username'];
        $result->firstname = $userRow['firstname'];
        $result->lastname = $userRow['lastname'];
        $result->email = $userRow['email'];
        $result->password = $userRow['password'];
        $result->date_joined = $userRow['date_joined'];
        $result->date_updated = $userRow['date_updated'];

        return $result;
    }

    public function getByUsername(string $username): UserReaderResult
    {
        $userRow = $this->repository->getByName($username);

        $result = new UserReaderResult();
        $result->id = $userRow['id'];
        $result->username = $userRow['username'];
        $result->firstname = $userRow['firstname'];
        $result->lastname = $userRow['lastname'];
        $result->email = $userRow['email'];
        $result->password = $userRow['password'];
        $result->date_joined = $userRow['date_joined'];
        $result->date_updated = $userRow['date_updated'];

        return $result;
    }
}
