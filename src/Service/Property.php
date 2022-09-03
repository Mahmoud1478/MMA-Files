<?php

namespace MMAE\Files\Service;

use MMAE\Files\Enum\PropertyEnum;

class Property
{

    public static function get(string $model,string $prop, mixed$default) :mixed
    {
        return config('files.models.' . $model . '.' . $prop, config('files.' . $prop, $default));
    }

    public static function mainFolder()
    {
        return config('files.main_folder', 'media');
    }

    public static function IsFileWithoutProcess(string $model) :bool
    {
        return (!static::get($model,prop(PropertyEnum::FileIsImage), true) || (!static::get($model,prop(PropertyEnum::FileResizeActivate), false) && !static::get($model,prop(PropertyEnum::FileThumbActivate), false)));
    }
    public static function IsMediaWithoutProcess(string $model) :bool
    {
        return (!static::get($model,prop(PropertyEnum::MediaIsImage), true) || (!static::get($model,prop(PropertyEnum::MediaResizeActivate), false) && !static::get($model,prop(PropertyEnum::MediaThumbActivate), false)));
    }
}
