<?php

namespace Mahmoud\Files\Service;

use Illuminate\Support\Facades\File;


class FileSystem
{
    public static function mkdir(string $path): bool
    {
        return File::makeDirectory($path);
    }

    public static function rmdir(string $path): bool
    {
        return File::deleteDirectory($path);
    }

    public static function deleteFiles(string | array $path): bool
    {
        return File::delete($path);
    }

    public static function scan(string $path): array
    {
        return scandir($path);
    }

}

