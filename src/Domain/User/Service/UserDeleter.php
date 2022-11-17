<?php

namespace App\Domain\User\Service;

use App\Domain\User\Repository\UserRepository;

final class UserDeleter
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function delete(int $userId): void
    {
        $this->repository->delete($userId);
    }
}