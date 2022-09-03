<?php

namespace MMAE\Files\Models;

use Illuminate\Http\UploadedFile;

interface MMAModel
{
     public static function getClassFolder(): string ;

     public static function getFileAttr(): string;

     public static function getMediaRequestField(): string ;

     public static function registerMediaColumns(int $index , UploadedFile $file): array;
}
