<?php

namespace App\Domain\User\Service;

use App\Domain\User\Repository\UserRepository;
use App\Factory\LoggerFactory;
use Psr\Log\LoggerInterface;

final class UserDeleter
{
    private UserRepository $repository;
    private LoggerInterface $logger;

    public function __construct(
        UserRepository $userRepository,
        LoggerFactory $loggerFactory
    ) {
        $this->repository;
        $this->logger = $loggerFactory
            ->addFileHandler('user_deleter.log')
            ->createLogger();
    }

    public function delete(int $userId): void
    {
        $this->repository->delete($userId);

        $this->logger->info(
            sprintf(
                'User deleted successfully: %s',
                $userId
            )
        );
    }
}
