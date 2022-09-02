<?php

namespace Mahmoud147\Files\Trait;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Mahmoud147\Files\Enum\AttributeEnum;
use Mahmoud147\Files\Enum\FolderEnum;
use Mahmoud147\Files\Enum\PropertyEnum;
use Mahmoud147\Files\Model\File;
use Mahmoud147\Files\Service\FileSystem;
use Mahmoud147\Files\Service\Path;
use Mahmoud147\Files\Service\Property;
use Mahmoud147\Files\Service\Upload;

trait HasFiles
{
    public static function boot(): void
    {
        parent::boot();
        static::creating(function ($item) {
            $attr = $item->{prop(AttributeEnum::File)}();
            if ($item->{$attr}) {
                $item->{$attr} = Upload::file($item::class,$item->{$attr});
            }
        });
        static::saved(function ($item) {
            $media =  request($item->{prop(AttributeEnum::RequestField)}());
            if ($media && Property::get($item::class,prop(PropertyEnum::MediaActivate),false)) {
               Upload::media($item,$media);
            }
        });
        static::updating(function($item){
            $attr = $item->{prop(AttributeEnum::File)}();
            if ($item->attributes[$attr] !== $item->original[$attr]) {
                Filesystem::deleteFiles(Path::getFileDiskPath($item::class,$item->original[$attr]));
                if(Property::get($item::class,prop(PropertyEnum::FileThumbActivate),false))
                {Filesystem::deleteFiles(Path::getFileDiskPath($item::class,prop(FolderEnum::Thumb).DIRECTORY_SEPARATOR.$item->original[$attr]));}
                $item->{$attr} = Upload::file($item::class,$item->{$attr});
            }
        });

        static::deleted(function ($item){
            $attr = $item->{prop(AttributeEnum::File)}();
            if ($item->{$attr}) {
                Filesystem::deleteFiles(Path::getFileDiskPath($item::class,$item->{$attr}));
                if(Property::get($item::class,prop(PropertyEnum::FileThumbActivate),false))
                {Filesystem::deleteFiles(Path::getFileDiskPath($item::class,prop(FolderEnum::Thumb).DIRECTORY_SEPARATOR.$item->{$attr}));}
            }
            if(Property::get($item::class,prop(PropertyEnum::MediaActivate),false)){
                $folders = $item->files()->pluck('folder');
                foreach ($folders as $folder){
                    FileSystem::rmdir(Path::getFileDiskPath($item::class,prop(FolderEnum::Media).DIRECTORY_SEPARATOR.$folder));
                }
                $item->files()->delete();
            }
        });
    }

    public function scopeWithFileUrl(Builder $query)
    {
        $attr = prop(AttributeEnum::File)();
        $query->addSelect(DB::raw('concat("' . Path::getFileUrl(__CLASS__) . '","/",' . $attr . ') as ' . $attr . '_url'));
    }


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

    public function files(): MorphMany
    {
        return $this->morphMany(File::class, 'model')
            ->selectRaw('*,concat("' . Path::getFileUrl(__CLASS__,prop(FolderEnum::Media)) . '","/",folder,"/",name) as url')
            ->when(Property::get(__CLASS__,prop(PropertyEnum::MediaThumbActivate), false) &&
                Property::get(__CLASS__,prop(PropertyEnum::MediaIsImage),  false),
                function (Builder $q) {
                    $q->selectRaw('concat("' . Path::getFileUrl(__CLASS__,prop(FolderEnum::Media)) . '","/",folder,"/","thumb_",name) as thumb');
                });
    }


    public static function registerMediaColumns(int $index , UploadedFile $file): array
    {
        return [];
    }

    public function deleteFiles(?array $ids , ?array $objects = null): static
    {
        if(Property::get(__CLASS__,prop(PropertyEnum::MediaActivate),false)){
            if ($ids){$folders = $this->files()->pluck('folder');}else{$ids = [];$folders = $objects??[];}
            foreach ($folders as $folder){
                FileSystem::rmdir(Path::getFileDiskPath($this::class,prop(FolderEnum::Media).DIRECTORY_SEPARATOR.$folder));
            }
            if ($ids) $this->files()->whereIn('id' , $ids)->delete();
        }
        return $this;
    }
}
