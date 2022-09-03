<?php

namespace MMA\Files\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use MMA\Files\Enum\AttributeEnum;
use MMA\Files\Enum\FolderEnum;
use MMA\Files\Enum\PropertyEnum;
use MMA\Files\Models\File;
use MMA\Files\Service\FileSystem;
use MMA\Files\Service\Path;
use MMA\Files\Service\Property;

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
