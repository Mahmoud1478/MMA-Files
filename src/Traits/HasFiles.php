<?php

namespace MMA\Files\Trait;

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
