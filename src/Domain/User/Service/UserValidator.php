<?php

namespace App\Domain\User\Service;

use App\Domain\User\Repository\UserRepository;
use App\Factory\ConstraintFactory;
use DomainException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validation;

final class UserValidator
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function validateUserUpdate(int $userId, array $data): void
    {
        if (!$this->repository->exists($userId)) {
            throw new DomainException(sprintf('User not found: %s', $userId));
        }

        $this->validateUser($data);
    }

    public function validateUser(array $data): void
    {
        $validator = Validation::createValidator();
        $violations = $validator->validate($data, $this->createConstraints());

        if ($violations->count()) {
            throw new ValidationFailedException('Please check your input', $violations);
        }
    }

    private function createConstraints(): Constraint
    {
        $constraint = new ConstraintFactory();

        return $constraint->collection(
            [
                'suUsername' => $constraint->required(
                    [
                        $constraint->notBlank(),
                        $constraint->length(null, 40),
                    ]
                 ),
                'suFirstName' => $constraint->required(
                    [
                        $constraint->notBlank(),
                        $constraint->length(null, 60),
                    ]
                ),
                'suLastName' => $constraint->required(
                    [
                        $constraint->notBlank(),
                        $constraint->length(null, 60),
                    ]
                ),
                'suEmail' => $constraint->required(
                    [
                        $constraint->notBlank(),
                        $constraint->email(),
                        $constraint->length(null, 255),
                    ]
                ),
                'suPassword' => $constraint->required(
                    [
                        $constraint->notBlank(),
                        $constraint->length(8, 40),
                    ]
                ),
                'suPassword-repeat' => $constraint->required(
                    [
                        $constraint->notBlank(),
                        $constraint->length(8, 40),
                    ]
                ),
            ]
        );
    }
}