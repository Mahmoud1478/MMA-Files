<?php

namespace Mahmoud147\Files\Service;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;
use Mahmoud147\Files\Enum\AttributeEnum;
use Mahmoud147\Files\Enum\FolderEnum;
use Mahmoud147\Files\Enum\PrefixEnum;
use Mahmoud147\Files\Enum\PropertyEnum;

class Upload
{
    public static function media(Model $model,array $files): void
    {
        $result = [];
        $time = now();
        foreach ($files as $index => $file){
            $data = array_merge(Shaper::media($model, $file,$time, $index),$model->{prop(AttributeEnum::CustomMediaColumns)}($index,$file)) ;
            FileSystem::mkdir(Path::getFileDiskPath($model::class, prop(FolderEnum::Media) . DIRECTORY_SEPARATOR . $data['folder']));
            if (Property::IsMediaWithoutProcess(__CLASS__)) {
                $file->move(Path::getFileDiskPath($model::class, prop(FolderEnum::Media) . DIRECTORY_SEPARATOR . $data['folder']), $data['name']);
            }else{
                $image = Image::make($file);
                if (Property::get($model::class, prop(PropertyEnum::MediaResizeActivate), false)) {
                    $image->resize(Property::get($model::class, prop(PropertyEnum::MediaResizeWidth), 1000), Property::get($model::class, prop(PropertyEnum::MediaResizeHeight), 1000));
                }
                $image->save(Path::getFileDiskPath($model::class, prop(FolderEnum::Media) . DIRECTORY_SEPARATOR . $data['folder'] . DIRECTORY_SEPARATOR . $data['name']), 72);
                if (Property::get($model::class,prop(PropertyEnum::MediaThumbActivate), false)) {
                    $image->resize(Property::get($model::class, prop(PropertyEnum::MediaThumbWidth), 100), Property::get($model::class,prop(PropertyEnum::MediaThumbWidth), 100))
                        ->save(Path::getFileDiskPath($model::class, prop(FolderEnum::Media) . DIRECTORY_SEPARATOR . $data['folder'] .DIRECTORY_SEPARATOR .prop(PrefixEnum::Thumb).'_' . $data['name']), 72);
                }
            }
            $result[] = $data;

        }
        foreach (array_chunk($result, 1000) as $data) {
            $model->files()->insert($data);
        }

    }
    public static function file( string $model,UploadedFile $file): string
    {
        $fileName = time().$file->getClientOriginalName();
        if (Property::IsFileWithoutProcess(__CLASS__)) {
            $file->move(Path::getFileDiskPath($model), $fileName);
            return $fileName;
        }
        $image = Image::make($file);
        if (Property::get($model,prop(PropertyEnum::FileResizeActivate), false)) {
            $image->resize(Property::get($model,prop(PropertyEnum::FileResizeWidth), 100), Property::get($model,prop(PropertyEnum::FileResizeHeight), 100));
        }
        $image->save(Path::getFileDiskPath($model,$fileName), 72);
        if (Property::get($model,prop(PropertyEnum::FileThumbActivate), false)) {
            $image->resize(Property::get($model,prop(PropertyEnum::FileThumbWidth), 100), Property::get($model,prop(PropertyEnum::FileThumbHeight), 100))
                ->save(Path::getFileDiskPath($model,prop(FolderEnum::Thumb).'/' . $fileName), 72);
        }
        return $fileName;
    }
}
