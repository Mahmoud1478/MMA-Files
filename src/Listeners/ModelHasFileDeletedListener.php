<?php

namespace MMA\Files\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use MMA\Files\Enum\AttributeEnum;
use MMA\Files\Enum\FolderEnum;
use MMA\Files\Enum\PropertyEnum;
use MMA\Files\Events\ModelHasFileDeleted;
use MMA\Files\Service\FileSystem;
use MMA\Files\Service\Path;
use MMA\Files\Service\Property;

class ModelHasFileDeletedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param ModelHasFileDeleted $event
     * @return void
     */
    public function handle(ModelHasFileDeleted $event)
    {
        $attr = $event->model->{prop(AttributeEnum::File)}();
            if ($event->model->{$attr}) {
                Filesystem::deleteFiles(Path::getFileDiskPath($event->model::class,$event->model->{$attr}));
                if(Property::get($event->model::class,prop(PropertyEnum::FileThumbActivate),false))
                {Filesystem::deleteFiles(Path::getFileDiskPath($event->model::class,prop(FolderEnum::Thumb).DIRECTORY_SEPARATOR.$event->model->{$attr}));}
            }
            if(Property::get($event->model::class,prop(PropertyEnum::MediaActivate),false)){
                $folders = $event->model->files()->pluck('folder');
                foreach ($folders as $folder){
                    FileSystem::rmdir(Path::getFileDiskPath($event->model::class,prop(FolderEnum::Media).DIRECTORY_SEPARATOR.$folder));
                }
                $event->model->files()->delete();
            }
    }
}
