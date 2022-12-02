<?php

namespace App\Domain\User\Data;

final class UserFinderItem
{
    public ?int $id = null;

    public ?string $username = null;

    public ?string $firstname = null;

    public ?string $lastname = null;

    public ?int $avatar_id = null;

    public ?string $email = null;

    public ?string $password = null;

    public ?string $date_joined = null;

    public ?string $date_updated = null;
}
