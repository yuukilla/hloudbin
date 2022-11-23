<?php

namespace App\Domain\User\Service;

use App\Domain\User\Repository\UserRepository;
use App\Factory\LoggerFactory;
use Psr\Log\LoggerInterface;

final class UserCreator
{
    private UserRepository $repository;

    private UserValidator $validator;

    private LoggerInterface $logger;

    public function __construct(
        UserRepository $repository,
        UserValidator $validator,
        LoggerFactory $logger
    ) {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->logger = $logger
            ->addFileHandler('user_creator.log')
            ->createLogger();
    }

    public function create(array $data): int
    {
        $this->validator->validateUser($data);

        $userId = $this->repository->create($data);

        $this->logger->info(sprintf('User created successfully: %s', $userId));

        return $userId;
    }
}
