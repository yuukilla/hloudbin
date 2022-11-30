<?php

namespace App\Domain\Storage\Data;

final class StorageFinderItem
{
    public ?int $id = null;

    public ?int $user_id = null;

    public ?string $type = null;
    
    public ?string $name = null;

    public ?int $visible = null;

    public ?string $upload_date = null;
}