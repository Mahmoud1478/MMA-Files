<?php

namespace MMAE\Files\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use MMAE\Files\Enum\AttributeEnum;
use MMAE\Files\Enum\FolderEnum;
use MMAE\Files\Enum\PropertyEnum;
use MMAE\Files\Models\File;
use MMAE\Files\Service\FileSystem;
use MMAE\Files\Service\Path;
use MMAE\Files\Service\Property;

trait HasFiles
{

    public static function getClassFolder(): string
    {
        $array = explode(DIRECTORY_SEPARATOR, __CLASS__);
        return strtolower(Str::plural(end($array)));
    }

    public static function getFileAttr(): string
    {
        return 'img';
    }

    public static function getMediaRequestField(): string
    {
        return 'files';
    }
    public static function registerMediaColumns(int $index , UploadedFile $file): array
    {
        return [];
    }



}
