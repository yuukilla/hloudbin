<?php

namespace App\Domain\User\Service;
use App\Domain\User\Data\UserReaderResult;
use App\Domain\User\Repository\UserRepository;

final class UserReader
{
    private UserRepository $repository;

    public function __construct(UserRepository $userRepository)
    {
        $this->repository = $userRepository;
    }

    public function getUserById(int $userId): UserReaderResult
    {
        $userRow = $this->repository->getUserById($userId);

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

    public function getUserByName(string $userName): UserReaderResult
    {
        $userRow = $this->repository->getUserByName($userName);

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
