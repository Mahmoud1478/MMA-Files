<?php

namespace MMAE\Files\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use MMAE\Files\Enum\AttributeEnum;
use MMAE\Files\Enum\FolderEnum;
use MMAE\Files\Enum\PropertyEnum;
use MMAE\Files\Events\ModelHasFilesUpdating;
use MMAE\Files\Service\FileSystem;
use MMAE\Files\Service\Path;
use MMAE\Files\Service\Property;
use MMAE\Files\Service\Upload;

class ModelHasFilesUpdatingListener
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
     * @param ModelHasFilesUpdating $event
     * @return void
     */
    public function handle(ModelHasFilesUpdating $event): void
    {
        $attr = $event->model->{prop(AttributeEnum::File)}();
        if ($event->model->attributes[$attr] !== $event->model->original[$attr]) {
            Filesystem::deleteFiles(Path::getFileDiskPath($event->model::class,$event->model->original[$attr]));
            if(Property::get($event->model::class,prop(PropertyEnum::FileThumbActivate),false))
            {Filesystem::deleteFiles(Path::getFileDiskPath($event->model::class,prop(FolderEnum::Thumb).DIRECTORY_SEPARATOR.$event->model->original[$attr]));}
            $event->model->{$attr} = Upload::file($event->model::class,$event->model->{$attr});
        }
    }
}
