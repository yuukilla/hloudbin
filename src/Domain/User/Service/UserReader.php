<?php

namespace APp\Domain\User\Service;

use App\Domain\User\Data\UserReaderResult;
use App\Domain\User\Repository\UserRepository;

final class UserReader
{
    private UserRepository $repository;

    public function __construct(
        UserRepository $userRepository
    ) {
        $this->repository = $userRepository;
    }

    public function getbyID(int $userId): UserReaderResult
    {
        $userRow = $this->repository->getbyID($userId);
        
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

    public function getbyName(string $userName): UserReaderResult
    {
        $userRow = $this->repository->getbyName($userName);

        $result = new UserReaderResult();
        $result->id = $userRow['id'];
        $result->username = $userRow['username'];
        $result->firstname = $userRow['firstname'];
        $result->lastname = $userRow['lastname'];
        $result->avatar_id = $userRow['avatar_id'];
        $result->email = $userRow['email'];
        $result->password = $userRow['password'];
        $result->date_joined = $userRow['date_joined'];
        $result->date_updated = $userRow['date_updated'];

        return $result;
    }
}
