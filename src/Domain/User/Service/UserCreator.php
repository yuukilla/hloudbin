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
        UserRepository $userRepository,
        UserValidator $userValidator,
        LoggerFactory $loggerFactory
    ) {
        $this->repository = $userRepository;
        $this->validator = $userValidator;
        $this->logger = $loggerFactory
            ->addFileHandler('user_creator.log')
            ->createLogger();
    }

    public function createUser(array $data): int
    {
        $this->validator->validateUser($data);
        
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        $userId = $this->repository->createUser($data);

        $this->logger->info(sprintf('User created successfully: %s', $userId));

        return $userId;
    }
}
