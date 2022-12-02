<?php

namespace App\Domain\Storage\Data;

final class StorageReaderResult
{
    public ?int $id = null;

    public ?int $user_id = null;

    public ?string $type = null;
    
    public ?string $filename = null;

    public ?string $basename = null;

    public ?int $visible = null;

    public ?string $upload_date = null;
}