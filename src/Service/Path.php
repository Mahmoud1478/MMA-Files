<?php

namespace MMAE\Files\Service;

use MMAE\Files\Enum\FolderEnum;

class Path
{

    public static function getFileDiskPath(string $model,?string $file = null): string
    {
        return public_path(Property::mainFolder(). DIRECTORY_SEPARATOR . $model::{prop(FolderEnum::Model)}(). DIRECTORY_SEPARATOR . $file);
    }

    public static function getFileUrl(string $model , ?string $file = null): string
    {
        return asset(Property::mainFolder() . '/' . $model::{prop(FolderEnum::Model)}() . '/' . $file);
    }

    public static function mainFolder()
    {
        return config('files.main_folder', 'media');
    }
}
