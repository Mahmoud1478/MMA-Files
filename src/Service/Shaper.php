<?php

namespace MMA\Files\Service;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use MMA\Files\Enum\MsgEnum;

class Shaper
{

    public static function media(Model $model ,UploadedFile $file,string $time,?string $suffix = null): array
    {
        return [
            'model_type' => $model::class,
            'model_id' => $model->id,
            'size' => $file->getSize(),
            'type' => $file->getMimeType(),
            'name' => $file->getClientOriginalName(),
            'extension'=> $file->extension(),
            'folder'=> time().$suffix,
            'created_at' => $time,
            'updated_at'=>$time,
        ];
    }

    public static function folderCreatedMsg($name): string
    {
        return $name .' '. prop(MsgEnum::FolderCreated);
    }
    public static function folderDeletedMsg($name): string
    {
        return $name .' '. prop(MsgEnum::FolderDeleted);
    }

}
