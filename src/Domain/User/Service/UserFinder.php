<?php

namespace App\Domain\User\Service;

use App\Domain\User\Data\UserFinderItem;
use App\Domain\User\Data\UserFinderResult;
use App\Domain\User\Repository\UserFinderRepository;

final class UserFinder
{
    private UserFinderRepository $repository;

    public function __construct(
        UserFinderRepository $userFinderRepository
    ) {
        $this->repository = $userFinderRepository;
    }

    public function find(): UserFinderResult
    {
        $users = $this->repository->find();

        return $this->result($users);
    }

    public function result(array $userRows): UserFinderResult
    {
        $result = new UserFinderResult();

        foreach ($userRows as $userRow) {
            $user = new UserFinderItem();
            $user->id = $userRow['id'];
            $user->username = $userRow['username'];
            $user->firstname = $userRow['firstname'];
            $user->lastname = $userRow['lastname'];
            $user->avatar_id = $userRow['avatar_id'];
            $user->email = $userRow['email'];
            $user->password = $userRow['password'];
            $user->date_joined = $userRow['date_joined'];
            $user->date_updated = $userRow['date_updated'];

            $result->users[] = $user;
        }

        return $result;
    }
}
